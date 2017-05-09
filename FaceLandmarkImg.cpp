// FaceLandmarkImg.cpp : Defines the entry point for the console application.
//

#include "LandmarkCoreIncludes.h"

// System includes
#include <fstream>

// OpenCV includes
#include <opencv2/core/core.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <opencv2/imgproc.hpp>

// Boost includes

#include <dlib/image_processing/frontal_face_detector.h>

#include <tbb/tbb.h>

vector<string> get_arguments(int argc, char **argv)
{

	vector<string> arguments;

	for (int i = 0; i < argc; ++i)
	{
		arguments.push_back(string(argv[i]));
	}
	return arguments;
}

void convert_to_grayscale(const cv::Mat& in, cv::Mat& out)
{
	if (in.channels() == 3)
	{
		// Make sure it's in a correct format
		if (in.depth() != CV_8U)
		{
			if (in.depth() == CV_16U)
			{
				cv::Mat tmp = in / 256;
				tmp.convertTo(tmp, CV_8U);
				cv::cvtColor(tmp, out, CV_BGR2GRAY);
			}
		}
		else
		{
			cv::cvtColor(in, out, CV_BGR2GRAY);
		}
	}
	else if (in.channels() == 4)
	{
		cv::cvtColor(in, out, CV_BGRA2GRAY);
	}
	else
	{
		if (in.depth() == CV_16U)
		{
			cv::Mat tmp = in / 256;
			out = tmp.clone();
		}
		else if (in.depth() != CV_8U)
		{
			in.convertTo(out, CV_8U);
		}
		else
		{
			out = in.clone();
		}
	}
}


void create_display_image(cv::Mat& display_image, vector<cv::Point> pts)
{
	int id = 1;
	for (cv::Point p : pts)
	{
		char digit[10];
		sprintf(digit,"%d", id);
		// A rough heuristic for drawn point size
		int thickness = (int)std::ceil(5.0* ((double)display_image.cols) / 640.0);
		int thickness_2 = (int)std::ceil(1.5* ((double)display_image.cols) / 640.0);

		cv::circle(display_image, p, 1, cv::Scalar(0, 0, 255), thickness, CV_AA);
		cv::circle(display_image, p, 1, cv::Scalar(255, 0, 0), thickness_2, CV_AA);
		cv::putText(display_image, digit, cv::Point(p.x, p.y - 10), cv::FONT_HERSHEY_COMPLEX, 0.6f,
			cv::Scalar(0, 0, 255), 1, CV_AA);
		id++;
	}
}


int main(int argc, char **argv)
{
	vector<string> files, depth_files, output_images, output_landmark_locations, output_pose_locations;
	// Bounding boxes for a face in each image (optional)
	vector<cv::Rect_<double> > bounding_boxes;

	vector<string> arguments = get_arguments(argc, argv);

	//Convert arguments to more convenient vector form
	LandmarkDetector::get_image_input_output_params(files, depth_files, output_landmark_locations, output_pose_locations, output_images, bounding_boxes, arguments);
	
	LandmarkDetector::FaceModelParameters det_parameters(arguments);
	// No need to validate detections, as we're not doing tracking
	det_parameters.validate_detections = false;
	bool visualise = false;

	for (int i = 0; i < arguments.size(); i++) {
		if (arguments[i].compare("-show") == 0)
			visualise = true;
	}

	// The modules that are being used for tracking
	cout << "Loading the model" << endl;
	LandmarkDetector::CLNF clnf_model(det_parameters.model_location);
	cout << "Model loaded" << endl;
	int max_width=1000;
	for (int fid = 0; fid < files.size(); fid++) {
		cv::Mat read_image = cv::imread(files[fid]);
		cv::Mat work_image;
		float scale = 1.0;
		if (read_image.cols > max_width) {
			scale = (float)max_width/read_image.cols;
			int max_height = read_image.rows*scale;

			cv::resize(read_image, work_image, cv::Size(max_width, max_height));
		}
		else work_image = read_image.clone();

		cv::Mat_<uchar> grayscale_image;
		convert_to_grayscale(work_image, grayscale_image);

		// Detect faces in an image
		vector<cv::Rect_<double> > face_detections;

		if (det_parameters.curr_face_detector == LandmarkDetector::FaceModelParameters::HOG_SVM_DETECTOR)
		{
			dlib::frontal_face_detector face_detector_hog = dlib::get_frontal_face_detector();
			vector<double> confidences;
			LandmarkDetector::DetectFacesHOG(face_detections, grayscale_image, face_detector_hog, confidences);
		}
		else
		{
			cv::CascadeClassifier classifier(det_parameters.face_detector_location);
			LandmarkDetector::DetectFaces(face_detections, grayscale_image, classifier);
		}

		FILE * out_f = NULL;
		if (!output_landmark_locations.empty())
		{
			char ext[5], output_path[1024];
			int len = output_landmark_locations[fid].length();
			strcpy(output_path, output_landmark_locations[fid].c_str());
			strcpy(ext, output_path + len - 4);
			if (strcmp(ext, ".pts") == 0) {
				output_path[len - 3] = 't';
				output_path[len - 2] = 'x';
				output_path[len - 1] = 't';
			}

			out_f = fopen(output_path, "wt");
		}
		
		// Detect landmarks around detected faces
		int face_det = 0;
		cv::Mat_<float> depth_image;

		// displaying detected landmarks
		cv::Mat display_image = read_image.clone();
		// perform landmark detection for every face detected
		for (size_t face = 0; face < face_detections.size(); ++face)
		{
			// if there are multiple detections go through them
			bool success = LandmarkDetector::DetectLandmarksInImage(grayscale_image, depth_image, face_detections[face], clnf_model, det_parameters);

			vector<cv::Point2d> pts2d = LandmarkDetector::CalculateLandmarks(clnf_model);

			vector<cv::Point> pts;
			for (int i = 0; i < pts2d.size(); i++)
			{
				cv::Point pt;
				pt.x = pts2d[i].x / scale;
				pt.y = pts2d[i].y / scale;
				pts.push_back(pt);
			}

			if (out_f != NULL)
			{
				char name[100];
				// append detection number (in case multiple faces are detected)
				fprintf(out_f, "face_%d", face_det);

				for (int pts_i = 0; pts_i < pts.size(); pts_i++) {
					int x = (int)pts[pts_i].x;
					int y = (int)pts[pts_i].y;
					fprintf(out_f, ",%d,%d", x, y);
				}
				fprintf(out_f, "\n");
			}

			create_display_image(display_image, pts);


			if (success)
			{
				face_det++;
			}

		}


		if (out_f != NULL) fclose(out_f);
		// Saving the display images (in an OS independent manner)
		if (!output_images.empty())
		{
			string outimage = output_images.at(fid);
			if (!outimage.empty())
			{
				bool write_success = cv::imwrite(outimage, display_image);

				if (!write_success)
				{
					cout << "Could not output a processed image" << endl;
					return 1;
				}

			}

		}

		if (visualise)
		{
			imshow("colour", display_image);
			cv::waitKey(0);
		}
	}

	


    return 0;
}


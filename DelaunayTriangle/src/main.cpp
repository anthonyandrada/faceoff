#include <opencv2/highgui/highgui.hpp>
#include <opencv2/imgproc/imgproc.hpp>
#include <vector>
#include <iostream>
#include <fstream>
#include <string>

using namespace cv;
using namespace std;

// Draw delaunay triangles
static void draw_delaunay(Mat& img, Subdiv2D& subdiv, Scalar delaunay_color) {

	vector<Vec6f> triangleList;
	subdiv.getTriangleList(triangleList);
	vector<Point> pt(3);
	Size size = img.size();
	Rect rect(0, 0, size.width, size.height);

	for (size_t i = 0; i < triangleList.size(); i++) {
		Vec6f t = triangleList[i];
		pt[0] = Point(cvRound(t[0]), cvRound(t[1]));
		pt[1] = Point(cvRound(t[2]), cvRound(t[3]));
		pt[2] = Point(cvRound(t[4]), cvRound(t[5]));

		// Draw rectangles completely inside the image.
		if (rect.contains(pt[0]) && rect.contains(pt[1])
				&& rect.contains(pt[2])) {
			line(img, pt[0], pt[1], delaunay_color, 1, CV_AA, 0);
			line(img, pt[1], pt[2], delaunay_color, 1, CV_AA, 0);
			line(img, pt[2], pt[0], delaunay_color, 1, CV_AA, 0);
		}
	}
}

// Draw a single point
static void draw_point(Mat& img, Point2f fp, Scalar color) {
	circle(img, fp, 2, color, CV_FILLED, CV_AA, 0);
}

int main(int argc, char** argv) {
	/* Local Variables */
	vector<Point2f> facial;
	vector<Point2f> pupils;
	int numOfFrames = 0;
	int countFrames = 1; //Default
	string filepath = "";
	string filename = "0000";
	string fileOut = "";
	string username = "";
	Scalar delaunay_color(255, 255, 255), points_color(0, 0, 255);
	/*
	 * Query Database for:
	 * @param argv[0] - unique video ID
	 *
	 * @return
	 * Number of frames - numOfFrames
	 * Width(pixels) of each frame - width
	 * Height(pixels) of each frame - height
	 * All facial data points - facial
	 * Filepath to folder - filepath
	 * Name of images - filename
	 * Pupil data points - pupils
	 */
	//Only Works if there is a parameter
	if (argc > 1) {
		filepath = argv[1];
		fileOut = argv[2];
        numOfFrames = argv[3];

		while (countFrames <= numOfFrames) {
			//extension type
			ostringstream convert;
			convert << countFrames;
			//file type change if need
			filename = convert.str() + ".png";
			if (countFrames < 1000) {
				//caps at 4 digits
				while (filename.size() <= 7) {
					filename = "0" + filename;
				}
			}
			convert.flush();
			//iterates till there are no more frames
			//name + frameID.png file
			//Mat img = imread(argv[0] + countFrames + ".png");
			Mat img = imread(filepath + filename);
			Mat img_clone = img.clone();
			// Rectangle to be used with Subdiv2D
			Size size = img.size();
			Rect rect(0, 0, size.width, size.height);

			//Apply subdiv to frame
			Subdiv2D subdiv(rect);

			//Extract pts from text file - comment out when database works.
			//read in format: x y

			//extension type

			filename = convert.str() + ".png_landmark.txt";
			if (countFrames < 1000) {
				//caps at 4 digits
				while (filename.size() <= 20) {
					filename = "0" + filename;
				}
			}
			filename = filepath + "landmark/" + filename;

			std::string delimiter = ",";

			size_t pos = 0;
			std::string line;


			ifstream ifs(filename.c_str());
			std::getline(ifs, line);
			string pt[137];
			int i = 0;
			while ((pos = line.find(delimiter)) != std::string::npos) {
				pt[i] = line.substr(0, pos);

				line.erase(0, pos + delimiter.length());
				i++;
			}
			int x, y;

			for (int j = 1; j < 135; j += 2) {
				x = atoi(pt[j].c_str());
				y = atoi(pt[j + 1].c_str());

				facial.push_back(Point2f(x, y));
			}

			ifs.close();

			//extract pupil points
			//Add all facial points to subdiv

			for (vector<Point2f>::iterator it = facial.begin();
					it != facial.end(); it++) {
				subdiv.insert(*it);
			}

			convert.flush();
			//Draw Delaunay

			draw_delaunay(img_clone, subdiv, delaunay_color);

			//Adding pupils to image
			for (vector<Point2f>::iterator it = pupils.begin();
					it != pupils.end(); it++) {
				draw_point(img_clone, *it, points_color);
			}
			//extension type
			//file type change if need
			filename = convert.str() + ".png";
			if (countFrames < 1000) {
				//caps at 4 digits
				while (filename.size() <= 7) {
					filename = "0" + filename;
				}
			}
			imwrite(fileOut + filename, img_clone);
			cout << fileOut + filename << endl;
			//Saves in directory
			//name = "/../tempImage/image" + countFrames + ".png";

			//iterate up
			facial.clear();
			pupils.clear();
			memset(&pt[0], 0, sizeof(pt));
			convert.flush();
			countFrames++;
		}
	}
	//End
	return 0;
}

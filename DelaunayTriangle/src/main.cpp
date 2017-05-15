#include <opencv2/highgui/highgui.hpp>
#include <opencv2/imgproc/imgproc.hpp>
#include <vector>
#include <iostream>
#include <fstream>
#include <string>
#include <pqxx/pqxx>
#include <stdio.h>

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

//pass filepath, username, video
int main(int argc, char** argv) { 
	/* Local Variables */
	vector<Point2f> facial;
	vector<Point2f> pupils;
	int numOfFrames = 3;
    double vID = 0;
	int countFrames = 1; //Default
	string fileIn = "";
    string fileOut = "";
    string video = "video.mp4";
	Scalar delaunay_color(255, 255, 255), points_color(0, 0, 255);
    char buffer [10];
    int data [77];
    int n=0, x=0, y=0;
    char str[10];
    char * pch;
	//Only Works if there is a parameter
	if (argc > 1) {
		fileIn = argv[1];
        fileOut = argv[2]
        username = argv[3];
        video = argv[4];
        
        /*
	 * Query Database for:
	 * @param argv[3] - username
     * @param argv[4] - videofile
	 *
	 * @return
	 * Number of frames - numOfFrames
	 * All facial data points - facial
	 * Filepath to folder - filepath
	 * Name of images - filename
	 * Pupil data points - pupils
	 */
    try {
      connection C("dbname = cs160 user = postgres password = student hostaddr = localhost port = 5432");
      if (C.is_open()) {
         cout << "Opened database successfully: " << C.dbname() << endl;
      } else {
         cout << "Can't open database" << endl;
         return 1;
      }
        /* Create SQL statement */
      sql = ("SELECT video_id, frames FROM video WHERE username = '%s', filename = '%s';", username, video);
        
      /* Create a non-transactional object. */
      nontransaction N(C);
      
      /* Execute SQL query */
      result R(N.exec(sql));
      
      /* List down all the records */
      for (result::const_iterator c = R.begin(); c != R.end(); ++c) {
          vID = c[0].as<double>();
          numOfFrames = c[1].as<int>();
      }

		while (countFrames <= numOfFrames) {
			n=sprintf (buffer, "%04d.png", countFrames);
            inputPath = fileIn + n;
			Mat img = imread(inputPath);
			Mat img_clone = img.clone();
			// Rectangle to be used with Subdiv2D
			Size size = img.size();
			Rect rect(0, 0, size.width, size.height);
			//Apply subdiv to frame
			Subdiv2D subdiv(rect);
            
            //DB QUERY
            sql = ("SELECT * FROM image WHERE video_id = '%lf' AND image_id = '%d';", vID, countFrames);
            result R(N.exec(sql));
            
           //Iterate through data points and push here
            for (result::const_iterator c = R.begin(); c != R.end(); ++c) {
                 //extract pupil points
                x = c[0].as<int>();
                y = c[1].as<int>();
                //extract facial data
                for (int i = 0; i < 68; i++)
                {
                    str = c[i+10].as<string>();
                    pch = strtok (str,"(,)");
                    x = pch[1];
                    y = pch[4];
                    if (x != 0 && y != 0) facial.push_back(Point2f(x, y));
                }
            }
            //push pupil points
            if (x != 0 && y != 0) pupils.push_back(Point2f(x, y));
            
            
			//Add all facial points to subdiv
			for (vector<Point2f>::iterator it = facial.begin();
					it != facial.end(); it++) {
				subdiv.insert(*it);
			}

			//Draw Delaunay
			draw_delaunay(img_clone, subdiv, delaunay_color);
			facial.empty();
			//Adding pupils to image
			for (vector<Point2f>::iterator it = pupils.begin();
					it != pupils.end(); it++) {
				draw_point(img_clone, *it, points_color);
			}
			pupils.empty();
			
			//write the file to output path
            outputPath = fileOut + n;
			imwrite(outputPath, img_clone);
			//iterate up
            memset(&buffer[0], 0, sizeof(buffer));
			convert.flush();
			countFrames++;
		}
        
        C.disconnect ();
	}
      catch (const std::exception &e) {
      cerr << e.what() << std::endl;
      return 1;
   }
}
	//End
	return 0;

}

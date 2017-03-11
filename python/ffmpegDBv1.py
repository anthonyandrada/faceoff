import subprocess
import yaml
import pprint
import psycopg2

#For testing
def printJSON(input):
    printer = pprint.PrettyPrinter()
    printer.pprint(input)

#Need to modify connection string to work with server DB
#Creates connection to db.
def connectDB():
    connection = "host=localhost dbname=testdb user=dude password=qwerty"
    try:
        conn = psycopg2.connect(connection)
    except psycopg2.Error as e:
        print(e)
    return conn

#Called from getMetadata method. Inserts metadata into video table
def insertToDB(data):
    conn = connectDB()
    cursor = conn.cursor()
    cursor.execute("INSERT INTO video VALUES (123, uuid_generate_v4(), {}, {}, {}, {}, 'user123')".format(data[1], data[2], data[3], data[4]))
    conn.commit()
    conn.close()
    return

#Just gets random stuff for now.
def getFromDB():
    conn = connectDB()
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM video, profile")
    #cursor.execute("SELECT * FROM profile, video WHERE profile.username = video.username")
    records = cursor.fetchall()
    conn.close()
    pprint.pprint(records)
    print(records[0][6])
    return


def getMetadata(vidPath):
    command = ['ffprobe', '-print_format', 'json', '-show_streams', vidPath]
    output = subprocess.check_output(command).decode('utf-8')
    output = yaml.load(output)
    height = output['streams'][0]['height']
    width = output['streams'][0]['width']
    totalFrames = output['streams'][0]['nb_frames']
    duration = output['streams'][1]['duration']
    avgFPS = output['streams'][0]['r_frame_rate']
    #store data into db
    result = [vidPath, int(totalFrames), width, height, eval(avgFPS), float(duration)]
    insertToDB(result)
    return result


def extractFrames(vidPath):
    uid = 123456 #fake UID
    folder = 'vid{}'.format(uid)
    makeFolder = ['mkdir', '-p', folder]
    command = ['ffmpeg', '-v', 'quiet', '-i', vidPath, './{}/frame%04d.png'.format(folder), '-hide_banner']
    subprocess.check_output(makeFolder)
    command2 = ['chmod', '555', './{}'.format(folder)] #5 = access only permission
    #subprocess.check_output()
    subprocess.check_output(command)
    return

#Need to pull info from db instead of these params
def mergePics(avgFPS, folder):
    command = ['ffmpeg', '-framerate', avgFPS, '-i', './{}/frame%04d.png'.format(folder), 'output.mp4']
    subprocess.check_output(command)
    return

#param = path to video. If video is in same folder as this then just enter in file name like below:
#print getMetadata('a.mp4')

#param = same as above example...:
#extractFrames('a.mp4')

#params = FPS as a string, the folder that the images got extracted to:
#mergePics('14', 'vid123456');

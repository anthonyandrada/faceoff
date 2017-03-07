import subprocess
import yaml

#For testing
def printJSON(input):
    import pprint
    printer = pprint.PrettyPrinter()
    printer.pprint(input)

#need to check if video format is supported or is video file
#need login user name + unique video ID generation
def getMetadata(vidPath):
    command = ['ffprobe', '-print_format', 'json', '-show_streams', vidPath]
    output = subprocess.check_output(command).decode('utf-8')
    output = yaml.load(output)
    #printJSON(output)
    height = output['streams'][0]['height']
    width = output['streams'][0]['width']
    totalFrames = output['streams'][0]['nb_frames']
    duration = output['streams'][1]['duration']
    avgFPS = output['streams'][0]['r_frame_rate']

    result = [height, width, int(totalFrames), float(duration), eval(avgFPS)]
    return result

def extractFrames(vidPath, uid, vid):
    folder = 'vid{}'.format(uid)
    makeFolder = ['mkdir', '-p', folder]
    command = ['ffmpeg', '-v', 'quiet', '-i', vidPath, './{}/{}.%d.png'.format(folder, vid), '-hide_banner']
    subprocess.check_output(makeFolder)
    subprocess.check_output(command)
    return
                            
def mergePics(avgFPS, folder):
    command = ['ffmpeg', '-framerate', avgFPS, '-i', './{}/{}.%d.png'.format(folder, vid), 'output.mp4']
    subprocess.check_output(command)
    return

# print getMetadata('a.mp4')
# extractFrames('a.mp4')
# mergePics('14', 'vid123456');

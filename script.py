import os
import numpy as np
import matplotlib.pyplot as plt
import csv
import datetime

## User config
pathLog = 'C:\\Users\\Tiago Cabral\\Documents\\prj d6t\\D6T Logger\\novo\\logfile.csv'
pathLogCam = 'C:\\Users\\Tiago Cabral\\Documents\\prj d6t\\D6T Logger\\novo\\logfile_cam.csv'

dateLimitLower = '2018-11-28,15:50:00'
dateLimitUpper = '2018-11-28,15:55:00'
## End of User config

fulldate = []
x = []
y = []
y2 = []
y2_l = []


def dateFormat(arg):
    return(datetime.datetime.strptime(arg, '%Y-%m-%d,%H:%M:%S'))

## Getting the info from the file
# Format is date,time,val
with open(pathLog,'r') as csvfile:
    plots=csv.reader(csvfile, delimiter=',')
    count = 0
    for row in plots:
        if (dateFormat(row[0] + ',' + row[1]) > dateFormat(dateLimitUpper)):
            break
        elif  (dateFormat(row[0] + ',' + row[1]) < dateFormat(dateLimitLower)):
            continue
        count += 1
        if count >30:
            break
        x.append(row[1])
        y.append(int(row[2]))
        fulldate.append(row[0] + ',' + row[1])

with open(pathLogCam, 'r') as csvfile:
    plots=csv.reader(csvfile, delimiter=',')
    count = 0
    for row in plots:
        if (dateFormat(row[0] + ',' + row[1]) > dateFormat(dateLimitUpper)):
            break
        elif  (dateFormat(row[0] + ',' + row[1]) < dateFormat(dateLimitLower)):
            continue
        if(fulldate[count]==(row[0] + ',' + row[1])):
            y2.append(int(row[3])*-1)
            y2_l.append(row[3])
        else:
            y2.append(0)
            y2_l.append(" ")
        count += 1
        if count >30:
            break

fig, ax = plt.subplots(figsize=(20,8))
width = 0.75 # the width of the bars 
ind = np.arange(len(y))  # the x locations for the groups
ax.barh(ind, y, width, color="blue")
ax.barh(ind, y2, width, color="darkgreen")
ax.yaxis.grid(color="gainsboro")
ax.set_axisbelow(True)
ax.set_yticks(ind)
ax.set_yticklabels(x, minor=False)
left, right = plt.xlim()
plt.xlim((right)*-1, right)
for i, v in enumerate(y):
    plt.text(v, i, " "+str(v), color='blue', va='center', fontweight='bold')
for i, v in enumerate(y2_l):
    plt.text(v-(right/30), i, v, color='darkgreen', va='center', fontweight='bold')
plt.title('Detection results')
plt.xlabel('People Detected')
plt.ylabel('Time')
plt.show()
#plt.savefig(os.path.join('test.png'), dpi=300, format='png', bbox_inches='tight')
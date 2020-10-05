from sensor import SHT20
import math
import time
import datetime

INTERVAL_REGISTER = 30
INTERVAL_READ = 5

CONSOLE_OUTPUT = False
CSV_DATA_LOG = False

def saveValue(file_name, value, oldValue):
    if oldValue != value:
        f = open("/opt/innotune/settings/gpio/" + file_name, "w")
        f.write(value)
        f.close()
    return value

def printData(enabled, temp, hum):
    if enabled:
        print(temp + " °C")
        print(hum + " %")
        print("-------------------")

def logData(enabled, temp, hum):
    if enabled:
        st = str(datetime.datetime.now())
        f = open("/opt/innotune/settings/gpio/sensor_log.csv", "a")
        f.write(st + ";" + temp + ";" + hum + "\n")
        f.close()

def tryConnect():
    device_online = False
    saveValue("sensor_online", "0", "1")

    while device_online == False:
        try:
            sht = SHT20(1, 0x40)
            device_online = True
            saveValue("sensor_online", "1", "0")
        except OSError:
            print("Error: sensor not found")
        if device_online == False:
            time.sleep(INTERVAL_REGISTER)

    return sht

sht = tryConnect()
old_temp = "0"
old_hum = "0"
old_online = "1"

while True:
    temp = "-1"
    hum = "-1"
    try:
        h, t = sht.all()
        temp = str(math.ceil(t.C))
        hum = str(math.ceil(h.RH))
        old_online = saveValue("sensor_online", "1", old_online)
    except OSError:
        print("Error: cannot read sensor data")
        old_online = saveValue("sensor_online", "0", old_online)


    printData(CONSOLE_OUTPUT, temp, hum)
    logData(CSV_DATA_LOG, temp, hum)

    old_temp = saveValue("sensor_temp", temp, old_temp)
    old_hum = saveValue("sensor_hum", hum, old_hum)

    time.sleep(INTERVAL_READ)

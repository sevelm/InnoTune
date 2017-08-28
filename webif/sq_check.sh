#!/bin/bash

##########################################################
# Config


##########################################################



#rate=$(grep "track start sample rate:" /tmp/sq_log.log | cut -d: -f5 | awk '{print $1}' >> /tmp/sq_check.log)


#opened device squeeze02 using format: S32_LE sample rate: 


while [ 1 ]
do 
#rate=$(grep "track start sample rate:" /tmp/sq_log.log | cut -d: -f5 | awk '{print $1}') 

rate=$(grep "opened device squeeze02 using format: S32_LE sample rate:" /tmp/sq_log.log | cut -d: -f6 | awk '{print $1}')


   if [[ $rate ]]; then 
         echo "$rate" >> /tmp/sq_check.log
         #echo "$(date)" "$rate" >> /tmp/sq_check.log
         #echo "$(date)" > /tmp/sq_log.log
         break
   fi
done



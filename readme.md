## Open meteo api


### Services
this project contains 1 service, wich is the OpenMeteoAPI, this service is responsible for fetching an forecast, storing an forecast in teh database, finding an forecats, deleting an forecast

### Routes
`/api/forecast`\
Requirements:\
latitutude: gps latitude\
longitude:  gps longitude\
type = week/day : week = forecast for 7 days, day = forecast for one day\

This will get an new forecast

`/api/forecast/update/all`\
Refreshes every record of the forecasts

### Schedules
Run this command:\
` php bin/console messenger:consume scheduler_default`\
this will schedule an task to run every 10 minutes, this will refresh the database records of every forecast stored

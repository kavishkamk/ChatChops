#load package
library(utils)
library(base)
library(methods)
library(graphics)
library(grDevices)
library(stats)
library(datasets)
library(dygraphs)
library(DBI)
library(RMySQL)
library(htmlwidgets)

source("..\\R_execute\\R_getConnection.R")
#get db Connection
mydb <- dbConnect(MySQL(), user=getUser(), password=getPwd(), dbname=getDBName(), host=getHost(), port=getPort())

args <- commandArgs(TRUE)
ye1 <- args[1]
ye2 <- args[2]

#get data from MySQL database
query <- paste0("SELECT h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, h11, h12, h13, h14, h15, h16, h17, h18, h19, h20, h21, h22, h23, h24 FROM analizeonlineeachdateh WHERE recDate >= '",ye1,"' AND recDate <= '",ye2,"';")
result1 <- dbSendQuery(mydb, query)
timeData <- fetch(result1)
dbClearResult(result1)

dbDisconnect(mydb)

#set data
data1 = (1:7)
data2 = c(sum(timeData[1,]),sum(timeData[2,]),sum(timeData[3,]),sum(timeData[4,]),sum(timeData[5,]),sum(timeData[6,]),sum(timeData[7,]))
Number_of_users = as.integer(data2)

data <- data.frame(time=data1,Number_of_users)
str(data)

p <- dygraph(data,showRoller: true, main = paste("Number of online users with each day of the ",ye1,"-",ye2," "), xlab = 'date of week', ylab = 'number of users' ) %>%
  dyAxis("x", valueRange = FALSE) %>%
  dyRangeSelector() %>%
  dyCrosshair(direction = "vertical") %>%
  dyHighlight(highlightCircleSize = 5, highlightSeriesBackgroundAlpha = 0.2, hideOnMouseOut = FALSE)  %>%
  dyRoller(rollPeriod = 1)

#save the widget
f<-"..\\RPlots\\userOnlineDataInGivenWeek.html"
saveWidget(p,file.path(normalizePath(dirname(f)),basename(f)), selfcontained = FALSE)
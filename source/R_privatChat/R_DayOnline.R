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
inputdate <- args[1]
yee = as.Date(inputdate)

#get data from MySQL database
query <- paste0("SELECT h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, h11, h12, h13, h14, h15, h16, h17, h18, h19, h20, h21, h22, h23, h24 FROM analizeonlineeachdateh WHERE recDate = '",yee,"';")
result1 <- dbSendQuery(mydb, query)
timeData <- fetch(result1)
dbClearResult(result1)

dbDisconnect(mydb)

#set data
data1 <- (1:24)
Number_of_users <- as.integer(timeData[1,])

data <- data.frame(time=c(data1),Number_of_users)
str(data)

# drow dygraph
p <- dygraph(data, main = paste("Number of online users in ",inputdate," "), xlab = 'Hours of the day(24h)', ylab = 'Number of users')  %>%
  dyOptions(labelsUTC = TRUE, fillGraph=TRUE, fillAlpha=0.1, drawGrid = TRUE, colors="#D8AE5A") %>%
  dySeries("Number_of_users", stepPlot = FALSE, color = "blue") %>%
  dyRangeSelector() %>%
  dyCrosshair(direction = "vertical") %>%
  dyRoller(rollPeriod = 1) %>%
  dyHighlight(highlightCircleSize = 5, highlightSeriesBackgroundAlpha = 0.2, hideOnMouseOut = FALSE)  %>%
  dyGroup(c("Number_of_users"), drawPoints = TRUE, color = c("blue"))
#save the widget
f<-"..\\RPlots\\userOnlineTimeInGivenDate.html"
saveWidget(p,file.path(normalizePath(dirname(f)),basename(f)), selfcontained = FALSE)
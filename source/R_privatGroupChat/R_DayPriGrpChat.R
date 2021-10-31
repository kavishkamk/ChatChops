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

#get db Connection
mydb <- dbConnect(MySQL(), user='root', password='', dbname='chatchops', host='localhost', port=3307)

args <- commandArgs(TRUE)
inputdate <- args[1]
yee = as.Date(inputdate)

#get data from MySQL database
query <- paste0("SELECT h1, h2, h3, h4, h5, h6, h7, h8, h9, h10, h11, h12, h13, h14, h15, h16, h17, h18, h19, h20, h21, h22, h23, h24 FROM analizeprigrpmsgeachdateh WHERE recDate = '",yee,"';")
result1 <- dbSendQuery(mydb, query)
timeData <- fetch(result1)
dbClearResult(result1)

dbDisconnect(mydb)

#set data
data1 <- (1:24)
number_of_private_grp_msg <- as.integer(timeData[1,])

data <- data.frame(time=c(data1),number_of_private_grp_msg)
str(data)

# drow dygraph
p <- dygraph(data, main = paste("Number of private group messages in ",inputdate," "), xlab = 'Hours of the day(24h)', ylab = 'Number of private group messages')  %>%
  dyOptions(labelsUTC = TRUE, fillGraph=TRUE, fillAlpha=0.1, drawGrid = TRUE, colors="#D8AE5A") %>%
  dySeries("number_of_private_grp_msg", stepPlot = FALSE, color = "blue") %>%
  dyRangeSelector() %>%
  dyCrosshair(direction = "vertical") %>%
  dyRoller(rollPeriod = 1) %>%
  dyHighlight(highlightCircleSize = 5, highlightSeriesBackgroundAlpha = 0.2, hideOnMouseOut = FALSE)  %>%
  dyGroup(c("number_of_private_grp_msg"), drawPoints = TRUE, color = c("blue"))

#save the widget
f<-"..\\RPlots\\privateGroupChatMsgTimeInGivenDate.html"
saveWidget(p,file.path(normalizePath(dirname(f)),basename(f)), selfcontained = FALSE)

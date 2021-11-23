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
ye <- args[1]
mon <- args[2]

#get data from MySQL database
query <- paste0("SELECT d1, d2, d3, d4, d5, d6, d7, d8, d9, d10, d11, d12, d13, d14, d15, d16, d17, d18, d19, d20, d21, d22, d23, d24, d25, d26, d27, d28, d29, d30, d31 FROM `analizeonlineeachmonthd` WHERE recYear = ",ye," AND recMonth = ",mon,";")
result1 <- dbSendQuery(mydb, query)
timeData <- fetch(result1)
dbClearResult(result1)

dbDisconnect(mydb)

#set data
data1 = (1:31)
number_of_users = as.integer(timeData[1,])

data <- data.frame(time=c(data1),number_of_users)
str(data)

# drow dygraph
p <- dygraph(data, main = paste("Number of online users with each day of the ",ye,"-",mon," "), xlab = 'Dates of Month', ylab = 'Number of users')  %>%
  dyOptions(labelsUTC = TRUE, fillGraph=TRUE, fillAlpha=0.1, drawGrid = TRUE, colors="#D8AE5A") %>%
  dySeries("number_of_users", stepPlot = FALSE, color = "blue") %>%
  dyRangeSelector() %>%
  dyCrosshair(direction = "vertical") %>%
  dyRoller(rollPeriod = 1) %>%
  dyHighlight(highlightCircleSize = 5, highlightSeriesBackgroundAlpha = 0.2, hideOnMouseOut = FALSE)  %>%
dyGroup(c("number_of_users"), drawPoints = TRUE, color = c("blue"))
#save the widget
f<-"..\\RPlots\\userOnlineDataInGivenMonth.html"
saveWidget(p,file.path(normalizePath(dirname(f)),basename(f)), selfcontained = FALSE)
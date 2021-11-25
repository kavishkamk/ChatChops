dbUser <- 'root'
dbpassword <- ''
dbname <- 'chatchops'
dbhost <- 'localhost'
dbPort <- 3307

getUser <- function(){
  return(dbUser)
}

getPwd <- function(){
  return(dbpassword)
}

getDBName <- function(){
  return(dbname)
}

getHost <- function(){
  return(dbhost)
}

getPort <- function(){
  return(dbPort)
}
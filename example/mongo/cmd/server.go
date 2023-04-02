package main

import (
	"log"
	"mongo/pkg"
	"mongo/util"
)

func main() {
	config := util.LoadConfig()

	server := util.RegisterServer()
	err := pkg.RegisterMongoDBService(server, config)
	if err != nil {
		log.Fatal(err)
	}
	server.Run("unix", config.Address)
}

package main

import (
	"flag"
	"fmt"
	"mongo/pkg"
)

func main() {
	var address string
	flag.StringVar(&address, "a", "/tmp/sidecar.sock", "sock地址")
	flag.Parse()
	client, err := pkg.DialClientTestMongoDB("unix", address)
	if err != nil {
		panic(err)
	}
	// test InsertOne
	var reply string
	requestOne := make(map[string]interface{}, 0)
	requestOne["name"] = "Hapi"
	requestOne["age"] = 30
	err = client.Database("test").Collection("test_sidecar").InsertOne(requestOne, &reply)
	if err != nil {
		panic(err)
	}
	fmt.Println(reply)

	// test InertMany
	requestMany := make([]interface{}, 0)
	requestMany = append(requestMany, requestOne)
	requestTwo := make(map[string]interface{}, 0)
	requestTwo["name"] = "Hapi"
	requestTwo["age"] = 28
	requestMany = append(requestMany, requestTwo)
	fmt.Println(requestMany)
	err = client.Database("test").Collection("test_sidecar").InsertMany(requestMany, &reply)
	if err != nil {
		panic(err)
	}
	fmt.Println(reply)

}

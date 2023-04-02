package main

import (
	"demo/pkg"
	"flag"
	"fmt"
)

func main() {
	var address string
	flag.StringVar(&address, "a", "/tmp/sidecar.sock", "sock地址")
	flag.Parse()
	client, err := pkg.DialClient("unix", address)
	if err != nil {
		panic(err)
	}
	var reply string
	err = client.Hello("Roc.fei", &reply)
	if err != nil {
		panic(err)
	}
	fmt.Println(reply)
	err = client.Test("Roc.xu", &reply)
	if err != nil {
		panic(err)
	}
	fmt.Println(reply)
}

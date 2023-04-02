package main

import (
	"demo/pkg"
	"demo/util"
	"flag"
)

func main() {
    var address string
    flag.StringVar(&address, "a", "/tmp/sidecar.sock", "sock地址")
    flag.Parse()
	server := util.RegisterServer()
	err := pkg.RegisterService(server)
	if err != nil {
		panic(err)
	}
	server.Run("unix", address)
}

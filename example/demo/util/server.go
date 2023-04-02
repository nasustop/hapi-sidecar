package util

import (
	goridgeRpc "github.com/roadrunner-server/goridge/v3/pkg/rpc"
	"net"
	"net/rpc"
)

func RegisterServer() *Server {
	return &Server{}
}

type Server struct {
}

func (s Server) Register(serviceName string, svc interface{}) error {
	return rpc.RegisterName(serviceName, svc)
}

func (s Server) Run(network string, address string) {
	listen, err := net.Listen(network, address)
	if err != nil {
		panic(err)
	}
	for {
		conn, err := listen.Accept()
		if err != nil {
			panic(err)
		}
		go rpc.ServeCodec(goridgeRpc.NewCodec(conn))
	}
}

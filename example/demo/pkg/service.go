package pkg

import (
	"demo/util"
)

func RegisterService(s *util.Server) error {
	service := new(DemoService)

	return s.Register("DemoService", service)
}

const ServiceName = "DemoService"

type DemoService struct {
}

func (s DemoService) Hello(request string, reply *string) error {
	*reply = "Hello " + request
	return nil
}

func (s DemoService) Test(request string, reply *string) error {
	*reply = "Test " + request
	return nil
}

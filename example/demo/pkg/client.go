package pkg

import (
	goridgeRpc "github.com/roadrunner-server/goridge/v3/pkg/rpc"
	"net"
	"net/rpc"
)

func DialClient(network string, address string) (*Client, error) {
	conn, err := net.Dial(network, address)
	if err != nil {
		return nil, err
	}
	c := rpc.NewClientWithCodec(goridgeRpc.NewClientCodec(conn))
	if err != nil {
		return nil, err
	}
	return &Client{
		Client: c,
	}, nil
}

type Client struct {
	*rpc.Client
}

func (c *Client) Hello(request string, reply *string) error {
	return c.Client.Call(ServiceName+".Hello", request, reply)
}

func (c *Client) Test(request string, reply *string) error {
	return c.Client.Call(ServiceName+".Test", request, reply)
}

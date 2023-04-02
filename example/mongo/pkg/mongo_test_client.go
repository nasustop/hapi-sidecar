package pkg

import (
	goridgeRpc "github.com/roadrunner-server/goridge/v3/pkg/rpc"
	"net"
	"net/rpc"
)

func DialClientTestMongoDB(network string, address string) (*MongoDBClient, error) {
	conn, err := net.Dial(network, address)
	if err != nil {
		return nil, err
	}
	c := rpc.NewClientWithCodec(goridgeRpc.NewClientCodec(conn))
	if err != nil {
		return nil, err
	}
	return &MongoDBClient{
		Client: c,
	}, nil
}

type MongoDBClient struct {
	*rpc.Client
	database   string
	collection string
}

func (c *MongoDBClient) Database(database string) *MongoDBClient {
	c.database = database
	return c
}

func (c *MongoDBClient) Collection(collection string) *MongoDBClient {
	c.collection = collection
	return c
}

func (c *MongoDBClient) InsertOne(request interface{}, reply *string) error {
	cmdRequest, err := EncodeInsertOneData(c.database, c.collection, request)
	if err != nil {
		return err
	}
	return c.Client.Call("MongoDB.InsertOne", cmdRequest, reply)
}

func (c *MongoDBClient) InsertMany(request []interface{}, reply *string) error {
	cmdRequest, err := EncodeInsertManyData(c.database, c.collection, request)
	if err != nil {
		return err
	}
	return c.Client.Call("MongoDB.InsertMany", cmdRequest, reply)
}

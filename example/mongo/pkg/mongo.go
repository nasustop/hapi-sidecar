package pkg

import (
	"context"
	"encoding/json"
	"fmt"
	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/mongo/options"
	"log"
	"mongo/util"
	"time"
)

func RegisterMongoDBService(s *util.Server, config util.Config) error {
	// 设置链接参数
	clientOptions := options.Client().ApplyURI(config.URI)

	ctx, cancel := context.WithTimeout(context.Background(), config.ConnectTimeout)
	defer cancel()
	// 连接数据库
	client, err := mongo.Connect(ctx, clientOptions)
	if err != nil {
		log.Fatal(err)
	}

	ctx, cancel = context.WithTimeout(context.Background(), config.ReadWriteTimeout)
	defer cancel()
	// 测试连接
	err = client.Ping(ctx, nil)
	if err != nil {
		log.Fatal(err)
	}
	service := &MongoDB{
		Client:           client,
		ReadWriteTimeout: config.ReadWriteTimeout,
	}
	return s.Register("MongoDB", service)
}

type MongoDB struct {
	Client           *mongo.Client
	ReadWriteTimeout time.Duration
}

func (c *MongoDB) InsertOne(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeInertOneData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result, err := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).InsertOne(ctx, cmdData.Document)
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = fmt.Sprintf("Insert Success: %s", result.InsertedID)
	return nil
}

func (c *MongoDB) InsertMany(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeInsertManyData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result, err := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).InsertMany(ctx, cmdData.Document)
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = fmt.Sprintf("Insert Success: %s", result.InsertedIDs)
	return nil
}

func (c *MongoDB) DeleteOne(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeFilterData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result, err := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).DeleteOne(ctx, cmdData.Filter)
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = fmt.Sprintf("Delete Num: %d", result.DeletedCount)
	return nil
}

func (c *MongoDB) DeleteMany(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeFilterData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result, err := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).DeleteMany(ctx, cmdData.Filter)
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = fmt.Sprintf("Delete Num: %d", result.DeletedCount)
	return nil
}

func (c *MongoDB) UpdateOne(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeUpdateData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result, err := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).UpdateOne(ctx, cmdData.Filter, cmdData.Update)
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = fmt.Sprintf("Delete Num: %s", result.UpsertedID)
	return nil
}

func (c *MongoDB) UpdateMany(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeUpdateData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result, err := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).UpdateMany(ctx, cmdData.Filter, cmdData.Update)
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = fmt.Sprintf("Delete Num: %s", result.UpsertedID)
	return nil
}

func (c *MongoDB) FindOneAndUpdate(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeUpdateData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).FindOneAndUpdate(ctx, cmdData.Filter, cmdData.Update)
	raw, err := result.DecodeBytes()
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = raw.String()
	return nil
}

func (c *MongoDB) FindOneAndReplace(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeUpdateData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).FindOneAndReplace(ctx, cmdData.Filter, cmdData.Update)
	raw, err := result.DecodeBytes()
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = raw.String()
	return nil
}

func (c *MongoDB) FindOne(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeFilterData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	result := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).FindOne(ctx, cmdData.Filter)
	raw, err := result.DecodeBytes()
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = raw.String()
	return nil
}

func (c *MongoDB) Find(request string, reply *string) error {
	// 解析cmd数据
	cmdData, err := DecodeFilterData(request)
	if err != nil {
		return err
	}
	// 执行mongo命令
	ctx, cancel := context.WithTimeout(context.Background(), c.ReadWriteTimeout)
	defer cancel()
	cur, err := c.Client.Database(cmdData.Database).Collection(cmdData.Collection).Find(ctx, cmdData.Filter)
	var results interface{}
	err = cur.All(ctx, results)
	if err != nil {
		return err
	}
	result, err := json.Marshal(results)
	if err != nil {
		return err
	}
	// 组装返回数据
	*reply = string(result)
	return nil
}

package pkg

import (
	"encoding/json"
	"fmt"
)

type InsertOneData struct {
	Database   string      `json:"database"`
	Collection string      `json:"collection"`
	Document   interface{} `json:"document"`
}

func DecodeInertOneData(request string) (*InsertOneData, error) {
	// 解析cmd数据
	var cmdData InsertOneData
	err := json.Unmarshal([]byte(request), &cmdData)
	if err != nil {
		return nil, err
	}
	// 判断database和collection不能为空
	if cmdData.Database == "" || cmdData.Collection == "" {
		return nil, fmt.Errorf("database 或者 collection 不能为空")
	}
	return &cmdData, nil
}

func EncodeInsertOneData(database, collection string, request interface{}) (string, error) {
	cmdData := InsertOneData{
		Database:   database,
		Collection: collection,
		Document:   request,
	}
	cmdRequest, err := json.Marshal(cmdData)
	if err != nil {
		return "", err
	}
	return string(cmdRequest), nil
}

type InsertManyData struct {
	Database   string        `json:"database"`
	Collection string        `json:"collection"`
	Document   []interface{} `json:"document"`
}

func DecodeInsertManyData(request string) (*InsertManyData, error) {
	// 解析cmd数据
	var cmdData InsertManyData
	err := json.Unmarshal([]byte(request), &cmdData)
	if err != nil {
		return nil, err
	}
	// 判断database和collection不能为空
	if cmdData.Database == "" || cmdData.Collection == "" {
		return nil, fmt.Errorf("database 或者 collection 不能为空")
	}
	return &cmdData, nil
}

func EncodeInsertManyData(database, collection string, request []interface{}) (string, error) {
	cmdData := InsertManyData{
		Database:   database,
		Collection: collection,
		Document:   request,
	}
	cmdRequest, err := json.Marshal(cmdData)
	if err != nil {
		return "", err
	}
	return string(cmdRequest), nil
}

type FilterData struct {
	Database   string      `json:"database"`
	Collection string      `json:"collection"`
	Filter     interface{} `json:"filter"`
}

func DecodeFilterData(request string) (*FilterData, error) {
	// 解析cmd数据
	var cmdData FilterData
	err := json.Unmarshal([]byte(request), &cmdData)
	if err != nil {
		return nil, err
	}
	// 判断database和collection不能为空
	if cmdData.Database == "" || cmdData.Collection == "" {
		return nil, fmt.Errorf("database 或者 collection 不能为空")
	}
	if cmdData.Filter == nil {
		return nil, fmt.Errorf("filter 不能为空")
	}
	return &cmdData, nil
}

func EncodeFilterData(database, collection string, request interface{}) (string, error) {
	cmdData := FilterData{
		Database:   database,
		Collection: collection,
		Filter:     request,
	}
	cmdRequest, err := json.Marshal(cmdData)
	if err != nil {
		return "", err
	}
	return string(cmdRequest), nil
}

type UpdateData struct {
	Database   string      `json:"database"`
	Collection string      `json:"collection"`
	Filter     interface{} `json:"filter"`
	Update     interface{} `json:"update"`
}

func DecodeUpdateData(request string) (*UpdateData, error) {
	// 解析cmd数据
	var cmdData UpdateData
	err := json.Unmarshal([]byte(request), &cmdData)
	if err != nil {
		return nil, err
	}
	// 判断database和collection不能为空
	if cmdData.Database == "" || cmdData.Collection == "" {
		return nil, fmt.Errorf("database 或者 collection 不能为空")
	}
	if cmdData.Filter == nil || cmdData.Update == nil {
		return nil, fmt.Errorf("filter 或者 update 不能为空")
	}
	return &cmdData, nil
}

func EncodeUpdateData(database, collection string, filter interface{}, update interface{}) (string, error) {
	cmdData := UpdateData{
		Database:   database,
		Collection: collection,
		Filter:     filter,
		Update:     update,
	}
	cmdRequest, err := json.Marshal(cmdData)
	if err != nil {
		return "", err
	}
	return string(cmdRequest), nil
}

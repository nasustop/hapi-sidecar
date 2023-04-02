package util

import (
	"flag"
	"time"
)

type Config struct {
	Address          string
	URI              string
	ConnectTimeout   time.Duration
	ReadWriteTimeout time.Duration
}

func LoadConfig() Config {
	var config Config
	var connectTimeout time.Duration
	var readWriteTimeout time.Duration
	flag.StringVar(&config.Address, "a", "/tmp/sidecar.sock", "sock地址")
	flag.StringVar(&config.URI, "h", "mongodb://localhost:27017", "mongo地址")
	flag.DurationVar(&connectTimeout, "ct", 5, "连接超时时间")
	flag.DurationVar(&readWriteTimeout, "rt", 5, "读写超时时间")
	config.ConnectTimeout = time.Second * connectTimeout
	config.ReadWriteTimeout = time.Second * readWriteTimeout
	flag.Parse()
	return config
}

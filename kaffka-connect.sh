#!/bin/bash

docker exec -it container bash

cd /opt/bitnami/kafka

echo "plugin.path=libs/connect-file-3.9.0.jar" >> config/connect-standalone.properties

sed -i 's/topics=connect-test/topics=test-3\n/g' config/connect-file-sink.properties
echo "key.converter=org.apache.kafka.connect.storage.StringConverter" >> config/connect-file-sink.properties
echo "value.converter=org.apache.kafka.connect.storage.StringConverter" >> config/connect-file-sink.properties
echo "key.converter.schemas.enable=false" >> config/connect-file-sink.properties
echo "value.converter.schemas.enable=false" >> config/connect-file-sink.properties

bin/connect-standalone.sh config/connect-standalone.properties config/connect-file-sink.properties
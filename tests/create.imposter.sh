echo "Installing imposters...";
curl --location --request POST 'http://mountebank:2525/imposters' \
--header 'Content-Type: application/json' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "port": "80",
    "hostname": "gateway.marvel.com",
    "protocol": "http",
    "recordRequests": "true",
    "name": "origin",
    "defaultResponse": {
        "statusCode": 404,
        "body": "Error"
    },
    "stubs": [
        {
            "predicates": [
                {
                    "contains": {
                        "method": "POST",
                        "path": "/emails"
                    }
                }
            ],
            "responses": [
                {
                    "is": {
                        "statusCode": 201,
                        "body": {
                            "status": "success"
                        }
                    }
                }
            ]
        }
    ]
}'
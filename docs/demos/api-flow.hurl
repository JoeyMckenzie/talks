# Step 1: Authenticate and get a token
POST https://httpbin.org/post
Content-Type: application/json
{
    "username": "demo_user",
    "password": "super_secret"
}
HTTP 200
[Captures]
auth_token: jsonpath "$.json.username"

# Step 2: Use the token to fetch data
GET https://httpbin.org/bearer
Authorization: Bearer {{auth_token}}
HTTP 200

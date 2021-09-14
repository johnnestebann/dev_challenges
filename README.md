## Planning Poker

### Get up and running

To run this code you need:
  - [Docker](https://www.docker.com/get-started) and [docker-compose](https://docs.docker.com/compose/install/) installed

Then:
  - Clone this repo: `git clone https://github.com/johnnestebann/dev_challenges.git`.
  - Run `make up`.
  
Check if services are up and running:
  - PHP backend in [http://localhost/api/v1/issue/1](http://localhost/api/v1/issue/1)


### Endpoints

##### `POST /api/v1/issue/{:issue}/join` - Used to join `{:issue}`. 
   - Must receive a payload with the intended name. ie: `{"name": "florencia"}`
   - If issue not exists generate a new one.
 
##### `POST /api/v1/issue/{:issue}/vote` - Used to vote `{:issue}`.
   - Must receive a payload with the vote value. `{"name": "florencia", "vote": 12}`
   - Send a -1 vote value to mark issue as passed `{"name": "florencia", "vote": -1}`
   - Reject votes when status of `{:issue}` is not `voting`. 
   - Reject votes if user not joined `{:issue}`. 
   - Reject votes if user already `voted` or `passed`.
  
##### `GET /api/v1/issue/{:issue}` - Returns the status of issue
   During `voting` the values votes are hidden until all members voted.
   - Issue is `voting`: 
        ````json
        {
            "status": "voting", 
            "members": {
                "John": {
                    "status": "voted",
                    "value": 0
                },
                "Paul": {
                    "status": "waiting",
                    "value": 0
                }
            },
            "avg": 0
         }
        ````
   - Issue is `reveal` when all users emitted their votes: 
        ````json
        {
            "status": "reveal", 
            "members": {
                "John": {
                    "status": "voted",
                    "value": 12
                },
                "Paul": {
                    "status": "voted",
                    "value": 10
                }
            },
            "avg": 11
         }
        ````

### Tools
- #### PHPUnit
  - Run `make test`
- #### PHPStan
  - Run `make stan`
- #### Sniff
  - Run `make sniff`
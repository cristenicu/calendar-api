# Calendar API

* Config db in `Api/Domain/Repository/BaseRepository::getConnection()`

### Use API:
* Get all events chronologically
    * GET `/api.php/calendar/`

* Get one event by id
    * GET `/api.php/calendar/{id}`
    
* Add new event
    * POST `/api.php/calendar/`
    * Params `x-www-form-urlencoded`:
        * `description` (String) 
        * `from_date` (String format `YYYY-mm-dd H:i:s`) 
        * `to_date` (String format `YYYY-mm-dd H:i:s`) 
        * `location` (String) 
        * `comment` (String) 

* Edit event by id
    * PUT `/api.php/calendar/{id}`
    * Params `x-www-form-urlencoded`:
        * `description` (String) 
        * `from_date` (String format `YYYY-mm-dd H:i:s`) 
        * `to_date` (String format `YYYY-mm-dd H:i:s`) 
        * `location` (String) 
        * `comment` (String) 
        
* Delete an event by id
    * DELETE `/api.php/calendar/{id}`
    
### API Response
All responses are in this format:

*`data` can be an `object` or an `array`
```json
{
    "status": 200,
    "message": "Response message",
    "data": {
        "id": "9",
        "description": "event description",
        "from_date": "YYYY-mm-dd H:i:s",
        "to_date": "YYYY-mm-dd H:i:s",
        "location": "event location",
        "comment": "event comment"
    }
}
```
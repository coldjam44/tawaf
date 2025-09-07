# Bot Offers API Documentation

## Endpoint: Get Bot Offer List

**URL:** `GET /api/bot/offers`

**Description:** Retrieves all projects that are in the bot offer list (where `is_sent_to_bot = true`)

### Response Format

```json
{
  "success": true,
  "message": "Bot offers retrieved successfully",
  "data": [
    {
      "id": 23,
      "title": "برج خليفة ريزيدنس 7",
      "area": "جزيرة الريم",
      "display_text": "برج خليفة ريزيدنس 7 في جزيرة الريم"
    },
    {
      "id": 22,
      "title": "أبراج هايدرا أفنيو",
      "area": "جزيرة الريم",
      "display_text": "أبراج هايدرا أفنيو في جزيرة الريم"
    }
  ],
  "count": 2,
  "timestamp": "2025-09-06T22:28:59.105757Z"
}
```

### Usage Examples

#### Node.js Bot Integration

```javascript
// Fetch bot offers
async function getBotOffers() {
  try {
    const response = await fetch('https://realestate.azsystems.tech/api/bot/offers');
    const data = await response.json();
    
    if (data.success) {
      console.log(`Found ${data.count} offers for bot`);
      return data.data;
    } else {
      console.error('Error:', data.message);
      return [];
    }
  } catch (error) {
    console.error('Network error:', error);
    return [];
  }
}

// Display offers to user
function displayOffers(offers) {
  offers.forEach((offer, index) => {
    console.log(`${index + 1}. ${offer.display_text}`);
    console.log(`   ID: ${offer.id}`);
    console.log(`   Title: ${offer.title}`);
    console.log(`   Area: ${offer.area}`);
    console.log('---');
  });
}

// Usage
getBotOffers().then(offers => {
  displayOffers(offers);
});
```

#### cURL Example

```bash
curl -X GET "https://realestate.azsystems.tech/api/bot/offers" \
  -H "Accept: application/json"
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Indicates if the request was successful |
| `message` | string | Success or error message |
| `data` | array | Array of project objects |
| `count` | integer | Number of projects in the bot list |
| `timestamp` | string | ISO timestamp of the response |

### Project Object Fields

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Unique project ID |
| `title` | string | Project title (Arabic preferred, falls back to English) |
| `area` | string | Area name (Arabic preferred, falls back to English) |
| `display_text` | string | Formatted text: "title في area" |

### Error Response

```json
{
  "success": false,
  "message": "Error retrieving bot offers",
  "error": "Detailed error message"
}
```

### Notes

- Only projects with `is_sent_to_bot = true` are returned
- Images include full URLs for easy access
- All text fields are provided in both Arabic and English
- The API is publicly accessible (no authentication required)
- Response includes timestamp for caching purposes

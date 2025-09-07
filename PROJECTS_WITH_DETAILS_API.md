# Projects With Details API Endpoint

## Overview
This endpoint provides comprehensive project information including all related data such as images, amenities, content blocks, and more.

## Endpoint
```
GET /api/projects/with-details
```

## Description
Retrieves all projects with their complete details including:
- Project title and description (Arabic/English)
- Area information
- Developer details
- Company information
- Project numbers (ADM, CN, Project Number, Madhmoun Permit Number)
- Brochure and floorplan files
- Project details
- Images organized by type (interior, exterior, floorplan, featured)
- Project descriptions
- Content blocks with images
- Amenities with names and icons

## Query Parameters

### Pagination
- `per_page` (optional): Number of items per page (default: 10, max: 100)
- `page` (optional): Page number (default: 1)

### Filtering
- `area` (optional): Filter by area ID
- `developer` (optional): Filter by developer ID
- `company` (optional): Filter by company ID
- `search` (optional): Search term for project titles, descriptions, and numbers

### Sorting
- `sort_by` (optional): Field to sort by (e.g., 'created_at', 'prj_title_ar', 'prj_title_en')
- `sort_direction` (optional): Sort direction - 'asc' or 'desc' (default: 'asc')

## Example Requests

### Basic Request
```http
GET /api/projects/with-details
```

### With Pagination
```http
GET /api/projects/with-details?per_page=20&page=2
```

### With Area Filter
```http
GET /api/projects/with-details?area=1
```

### With Search
```http
GET /api/projects/with-details?search=luxury&per_page=15
```

### With Multiple Filters
```http
GET /api/projects/with-details?area=1&developer=2&company=1&per_page=25
```

### With Sorting
```http
GET /api/projects/with-details?sort_by=created_at&sort_direction=desc
```

## Response Format

### Success Response (200)
```json
{
    "status": "success",
    "message": "Projects with details retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "title": {
                    "ar": "عنوان المشروع",
                    "en": "Project Title",
                    "current": "Project Title"
                },
                "description": {
                    "ar": "وصف المشروع",
                    "en": "Project Description",
                    "current": "Project Description"
                },
                "area": {
                    "id": 1,
                    "name": {
                        "ar": "اسم المنطقة",
                        "en": "Area Name"
                    },
                    "slug": "area-slug"
                },
                "developer": {
                    "id": 1,
                    "name": {
                        "ar": "اسم المطور",
                        "en": "Developer Name"
                    },
                    "image": "http://your-domain.com/developers/image.png",
                    "email": "developer@example.com",
                    "phone": "+971501234567"
                },
                "company": {
                    "id": 1,
                    "name": {
                        "ar": "اسم الشركة",
                        "en": "Company Name"
                    },
                    "logo": "http://your-domain.com/real-estate-companies/logo.png",
                    "company_images": [
                        "http://your-domain.com/real-estate-companies/image1.jpg",
                        "http://your-domain.com/real-estate-companies/image2.jpg"
                    ],
                    "contact_number": "+971501234567"
                },
                "project_numbers": {
                    "adm": "ADM-001",
                    "cn": "CN-001",
                    "project_number": "PROJ-001",
                    "madhmoun_permit_number": "PERMIT-001"
                },
                "files": {
                    "brochure": "http://your-domain.com/projects/brochures/brochure.pdf",
                    "floorplan": "http://your-domain.com/projects/floorplans/floorplan.pdf"
                },
                "project_details": [
                    {
                        "id": 1,
                        "detail": {
                            "ar": "تفاصيل المشروع",
                            "en": "Project Detail"
                        },
                        "detail_value": {
                            "ar": "قيمة التفاصيل",
                            "en": "Detail Value"
                        },
                        "order": 1
                    }
                ],
                "images": {
                    "interior": [
                        {
                            "id": 1,
                            "image_path": "http://your-domain.com/projects/images/interior1.jpg",
                            "title": {
                                "ar": "عنوان الصورة",
                                "en": "Image Title"
                            },
                            "description": {
                                "ar": "وصف الصورة",
                                "en": "Image Description"
                            },
                            "order": 1,
                            "is_featured": true
                        }
                    ],
                    "exterior": [],
                    "floorplan": [],
                    "featured": []
                },
                "descriptions": [
                    {
                        "id": 1,
                        "section_type": "about",
                        "title": {
                            "ar": "عنوان الوصف",
                            "en": "Description Title"
                        },
                        "content": {
                            "ar": "محتوى الوصف",
                            "en": "Description Content"
                        },
                        "order_index": 1,
                        "is_active": true
                    }
                ],
                "content_blocks": [
                    {
                        "id": 1,
                        "title": {
                            "ar": "عنوان الكتلة",
                            "en": "Block Title"
                        },
                        "content": {
                            "ar": "محتوى الكتلة",
                            "en": "Block Content"
                        },
                        "order": 1,
                        "is_active": true,
                        "images": [
                            {
                                "id": 1,
                                "image_path": "http://your-domain.com/projects/content-blocks/image1.jpg",
                                "title": {
                                    "ar": "عنوان الصورة",
                                    "en": "Image Title"
                                },
                                "description": {
                                    "ar": "وصف الصورة",
                                    "en": "Image Description"
                                },
                                "order": 1
                            }
                        ]
                    }
                ],
                "amenities": [
                    {
                        "id": 1,
                        "type": "infinity_pool",
                        "name": {
                            "ar": "مسبح لانهائي",
                            "en": "Infinity Pool"
                        },
                        "icon": "fas fa-swimming-pool",
                        "is_active": true
                    }
                ],
                "created_at": "2024-01-01T00:00:00.000000Z",
                "updated_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "first_page_url": "http://your-domain.com/api/projects/with-details?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://your-domain.com/api/projects/with-details?page=1",
        "links": [...],
        "next_page_url": null,
        "path": "http://your-domain.com/api/projects/with-details",
        "per_page": 10,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

### Error Response (500)
```json
{
    "status": "error",
    "message": "Failed to retrieve projects with details",
    "error": "Error details"
}
```

## Image Types

The endpoint organizes images into four categories:

1. **interior**: Interior project images
2. **exterior**: Exterior project images  
3. **floorplan**: Floor plan images
4. **featured**: Featured project images

## Amenity Types

Available amenity types include:
- `infinity_pool` - Infinity Pool
- `concierge_services` - Concierge Services
- `retail_fnb` - Retail and F&B
- `bbq_area` - BBQ Area
- `cinema_game_room` - Cinema and Game Room
- `gym` - State-of-the-art Gym
- `wellness_facilities` - Wellness Facilities
- `splash_pad` - Splash Pad
- `sauna_wellness` - Sauna & Wellness Facilities
- `multipurpose_court` - Multipurpose Court & Jogging Track

## File URLs

- **Brochures**: `/projects/brochures/{filename}`
- **Floorplans**: `/projects/floorplans/{filename}`
- **Project Images**: `/projects/images/{filename}`
- **Content Block Images**: `/projects/content-blocks/{filename}`
- **Developer Images**: `/developers/{filename}`
- **Company Logos**: `/real-estate-companies/{filename}`
- **Company Images**: `/real-estate-companies/{filename}`

## Performance Notes

- The endpoint uses eager loading to minimize database queries
- Images are organized by type for efficient frontend rendering
- Pagination is implemented to handle large datasets
- File URLs are generated using Laravel's `asset()` helper

## Rate Limiting

This endpoint follows the standard API rate limiting:
- 60 requests per minute per IP address

## Authentication

Currently, this endpoint does not require authentication, but it can be easily protected by adding middleware if needed.

## Usage Examples

### Frontend Integration
```javascript
// Fetch projects with details
fetch('/api/projects/with-details?per_page=20&area=1')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const projects = data.data.data;
            projects.forEach(project => {
                console.log(`Project: ${project.title.current}`);
                console.log(`Area: ${project.area.name.current}`);
                console.log(`Interior Images: ${project.images.interior.length}`);
                console.log(`Amenities: ${project.amenities.length}`);
            });
        }
    });
```

### Mobile App Integration
```dart
// Flutter/Dart example
Future<List<Project>> fetchProjectsWithDetails({
  int? areaId,
  String? searchTerm,
  int perPage = 10,
}) async {
  final queryParams = <String, String>{};
  if (areaId != null) queryParams['area'] = areaId.toString();
  if (searchTerm != null) queryParams['search'] = searchTerm;
  queryParams['per_page'] = perPage.toString();
  
  final uri = Uri.parse('/api/projects/with-details').replace(queryParameters: queryParams);
  final response = await http.get(uri);
  
  if (response.statusCode == 200) {
    final data = json.decode(response.body);
    return (data['data']['data'] as List)
        .map((json) => Project.fromJson(json))
        .toList();
  }
  throw Exception('Failed to load projects');
}
```

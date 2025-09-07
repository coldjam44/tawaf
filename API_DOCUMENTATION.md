# Real Estate API Documentation

## Overview
This API provides comprehensive endpoints for managing a real estate platform, including projects, developers, areas, and more.

## Base URL
```
https://your-domain.com/api
```

## Authentication
Currently, the API uses Laravel Sanctum for authentication. Add the following header to authenticated requests:
```
Authorization: Bearer {your-token}
```

## Response Format
All API responses follow this standard format:
```json
{
    "status": "success|error",
    "message": "Human readable message",
    "data": {...},
    "error": "Error details (if status is error)"
}
```

---

## 📋 SLIDERS API

### Get All Sliders
```http
GET /api/sliders
```

### Create Slider
```http
POST /api/sliders
Content-Type: multipart/form-data

{
    "title_ar": "عنوان السلايدر",
    "title_en": "Slider Title",
    "subtitle_ar": "العنوان الفرعي",
    "subtitle_en": "Subtitle",
    "button1_text_ar": "زر أول",
    "button1_text_en": "First Button",
    "button1_link": "https://example.com",
    "button2_text_ar": "زر ثاني",
    "button2_text_en": "Second Button",
    "button2_link": "https://example.com",
    "features_ar": "المميزات",
    "features_en": "Features",
    "brochure_link": "https://example.com/brochure.pdf",
    "status": "active|inactive",
    "order": 1,
    "background_image": "file"
}
```

### Get Single Slider
```http
GET /api/sliders/{id}
```

### Update Slider
```http
PUT /api/sliders/{id}
```

### Delete Slider
```http
DELETE /api/sliders/{id}
```

---

## 🏢 PROJECTS API

### Get All Projects
```http
GET /api/projects?search=term&area=1&per_page=10
```

### Create Project
```http
POST /api/projects
Content-Type: multipart/form-data

{
    "prj_title_ar": "عنوان المشروع",
    "prj_title_en": "Project Title",
    "prj_description_ar": "وصف المشروع",
    "prj_description_en": "Project Description",
    "prj_areaId": 1,
    "company_id": 1,
    "prj_adm": "ADM Number",
    "prj_cn": "CN Number",
    "prj_projectNumber": "PROJ-001",
    "prj_MadhmounPermitNumber": "PERMIT-001",
    "prj_brochurefile": "file",
    "prj_floorplan": "file"
}
```

### Get Single Project
```http
GET /api/projects/{id}
```

### Update Project
```http
PUT /api/projects/{id}
```

### Delete Project
```http
DELETE /api/projects/{id}
```

### Search Projects
```http
GET /api/search/projects?q=search_term&per_page=10
```

---

## 📍 AREAS API

### Get All Areas
```http
GET /api/areas
```

### Create Area
```http
POST /api/areas

{
    "name_ar": "اسم المنطقة",
    "name_en": "Area Name"
}
```

### Get Single Area
```http
GET /api/areas/{id}
```

### Update Area
```http
PUT /api/areas/{id}
```

### Delete Area
```http
DELETE /api/areas/{id}
```

### Search Areas
```http
GET /api/search/areas?q=search_term
```

---

## 🏗️ DEVELOPERS API

### Get All Developers
```http
GET /api/developers
```

### Create Developer
```http
POST /api/developers

{
    "name_ar": "اسم المطور",
    "name_en": "Developer Name",
    "email": "developer@example.com",
    "phone": "+971501234567",
    "address_ar": "عنوان المطور",
    "address_en": "Developer Address",
    "logo": "file",
    "background_image": "file",
    "status": "active|inactive",
    "order": 1
}
```

### Get Single Developer
```http
GET /api/developers/{id}
```

### Update Developer
```http
PUT /api/developers/{id}
```

### Delete Developer
```http
DELETE /api/developers/{id}
```

### Search Developers
```http
GET /api/search/developers?q=search_term
```

---

## 🏢 REAL ESTATE COMPANY API

### Get Company Info
```http
GET /api/real-estate-company
```

### Create Company Info
```http
POST /api/real-estate-company

{
    "company_name_ar": "اسم الشركة",
    "company_name_en": "Company Name",
    "description_ar": "وصف الشركة",
    "description_en": "Company Description",
    "logo": "file",
    "background_image": "file"
}
```

### Get Single Company Info
```http
GET /api/real-estate-company/{id}
```

### Update Company Info
```http
PUT /api/real-estate-company/{id}
```

### Delete Company Info
```http
DELETE /api/real-estate-company/{id}
```

---

## 📄 ABOUT US API

### Get About Us Info
```http
GET /api/about-us
```

### Create About Us Section
```http
POST /api/about-us

{
    "title_ar": "عنوان القسم",
    "title_en": "Section Title",
    "content_ar": "محتوى القسم",
    "content_en": "Section Content",
    "image": "file",
    "order": 1
}
```

### Get Single About Us Section
```http
GET /api/about-us/{id}
```

### Update About Us Section
```http
PUT /api/about-us/{id}
```

### Delete About Us Section
```http
DELETE /api/about-us/{id}
```

### Delete About Us Image
```http
DELETE /api/about-us/{sectionId}/images/{imageId}
```

---

## 📝 BLOG API

### Get All Blogs
```http
GET /api/blogs
```

### Create Blog
```http
POST /api/blogs

{
    "title_ar": "عنوان المقال",
    "title_en": "Blog Title",
    "content_ar": "محتوى المقال",
    "content_en": "Blog Content",
    "image": "file",
    "status": "published|draft"
}
```

### Get Single Blog
```http
GET /api/blogs/{id}
```

### Update Blog
```http
PUT /api/blogs/{id}
```

### Delete Blog
```http
DELETE /api/blogs/{id}
```

### Delete Blog Image
```http
DELETE /api/blogs/{blogId}/images/{imageId}
```

### Search Blogs
```http
GET /api/search/blogs?q=search_term
```

---

## 🏆 AWARDS API

### Get All Awards
```http
GET /api/awards
```

### Create Award
```http
POST /api/awards

{
    "title_ar": "اسم الجائزة",
    "title_en": "Award Title",
    "description_ar": "وصف الجائزة",
    "description_en": "Award Description",
    "image": "file",
    "year": 2024
}
```

### Get Single Award
```http
GET /api/awards/{id}
```

### Update Award
```http
PUT /api/awards/{id}
```

### Delete Award
```http
DELETE /api/awards/{id}
```

---

## 👥 EXPERT TEAM API

### Get All Team Members
```http
GET /api/expert-team
```

### Create Team Member
```http
POST /api/expert-team

{
    "name_ar": "اسم العضو",
    "name_en": "Member Name",
    "position_ar": "المنصب",
    "position_en": "Position",
    "bio_ar": "السيرة الذاتية",
    "bio_en": "Biography",
    "image": "file",
    "order": 1
}
```

### Get Single Team Member
```http
GET /api/expert-team/{id}
```

### Update Team Member
```http
PUT /api/expert-team/{id}
```

### Delete Team Member
```http
DELETE /api/expert-team/{id}
```

---

## 🎯 ACHIEVEMENTS API

### Get All Achievements
```http
GET /api/achievements
```

### Create Achievement
```http
POST /api/achievements

{
    "title_ar": "اسم الإنجاز",
    "title_en": "Achievement Title",
    "description_ar": "وصف الإنجاز",
    "description_en": "Achievement Description",
    "image": "file",
    "year": 2024
}
```

### Get Single Achievement
```http
GET /api/achievements/{id}
```

### Update Achievement
```http
PUT /api/achievements/{id}
```

### Delete Achievement
```http
DELETE /api/achievements/{id}
```

---

## 🔧 SERVICES API

### Get All Services
```http
GET /api/services
```

### Create Service
```http
POST /api/services

{
    "title_ar": "اسم الخدمة",
    "title_en": "Service Title",
    "description_ar": "وصف الخدمة",
    "description_en": "Service Description",
    "icon": "service-icon",
    "order": 1
}
```

### Get Single Service
```http
GET /api/services/{id}
```

### Update Service
```http
PUT /api/services/{id}
```

### Delete Service
```http
DELETE /api/services/{id}
```

---

## 📞 CONTACT US API

### Get Contact Information
```http
GET /api/contact-us
```

### Create Contact Information
```http
POST /api/contact-us

{
    "company_name_ar": "اسم الشركة",
    "company_name_en": "Company Name",
    "broker_registration_number": "RERA-123456",
    "phone_numbers": [
        {
            "number": "+971501234567",
            "type": "mobile"
        }
    ],
    "email_addresses": [
        {
            "email": "info@company.com",
            "type": "general"
        }
    ],
    "locations": [
        {
            "address_ar": "عنوان الشركة",
            "address_en": "Company Address",
            "map_embed": "<iframe>...</iframe>"
        }
    ],
    "map_embed": "<iframe>...</iframe>"
}
```

### Get Single Contact Info
```http
GET /api/contact-us/{id}
```

### Update Contact Information
```http
PUT /api/contact-us/{id}
```

### Delete Contact Information
```http
DELETE /api/contact-us/{id}
```

---

## 📧 NEWSLETTER API

### Get All Newsletter Subscriptions
```http
GET /api/newsletter?per_page=10
```

### Create Newsletter Subscription
```http
POST /api/newsletter

{
    "name": "اسم المشترك",
    "email": "subscriber@example.com",
    "phone": "+971501234567",
    "message": "رسالة المشترك"
}
```

### Get Single Newsletter Subscription
```http
GET /api/newsletter/{id}
```

### Update Newsletter Subscription
```http
PUT /api/newsletter/{id}
```

### Delete Newsletter Subscription
```http
DELETE /api/newsletter/{id}
```

---

## 🌍 VISITOR LOCATION API

### Get Visitor Country
```http
GET /api/visitor/country
```

### Get Visitor Details
```http
GET /api/visitor/details
```

### Get Country by IP
```http
GET /api/visitor/country/{ip}
```

### Alternative Routes
```http
GET /api/country
GET /api/my-location
```

---

## 📊 DASHBOARD STATISTICS API

### Get Dashboard Statistics
```http
GET /api/dashboard/stats
```

Response:
```json
{
    "status": "success",
    "data": {
        "total_projects": 25,
        "total_developers": 10,
        "total_areas": 15,
        "total_blogs": 30,
        "total_newsletters": 150,
        "total_contact_requests": 45
    }
}
```

---

## 🔍 SEARCH API

### Search Projects
```http
GET /api/search/projects?q=search_term&per_page=10
```

### Search Developers
```http
GET /api/search/developers?q=search_term
```

### Search Areas
```http
GET /api/search/areas?q=search_term
```

### Search Blogs
```http
GET /api/search/blogs?q=search_term
```

---

## 📁 PROJECT SUB-APIS

### Project Details
```http
GET /api/projects/{project}/details
POST /api/projects/{project}/details
GET /api/projects/{project}/details/{detail}
PUT /api/projects/{project}/details/{detail}
DELETE /api/projects/{project}/details/{detail}
```

### Project Images
```http
GET /api/projects/{project}/images
POST /api/projects/{project}/images
GET /api/projects/{project}/images/{image}
PUT /api/projects/{project}/images/{image}
DELETE /api/projects/{project}/images/{image}
```

### Project Descriptions
```http
GET /api/projects/{project}/descriptions
POST /api/projects/{project}/descriptions
GET /api/projects/{project}/descriptions/{description}
PUT /api/projects/{project}/descriptions/{description}
DELETE /api/projects/{project}/descriptions/{description}
```

### Project Amenities
```http
GET /api/projects/{project}/amenities
POST /api/projects/{project}/amenities
PUT /api/projects/{project}/amenities/{amenity}
DELETE /api/projects/{project}/amenities/{amenity}
POST /api/projects/{project}/amenities/{amenity}/toggle
POST /api/projects/{project}/amenities/bulk-update
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthorized |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

## Rate Limiting
API requests are limited to 60 requests per minute per IP address.

## File Uploads
- Maximum file size: 5MB
- Supported image formats: JPEG, PNG, JPG, GIF
- Supported document formats: PDF, DOC, DOCX

## Pagination
Most list endpoints support pagination with the following parameters:
- `per_page`: Number of items per page (default: 10, max: 100)
- `page`: Page number (default: 1)

Response includes pagination metadata:
```json
{
    "data": [...],
    "current_page": 1,
    "last_page": 5,
    "per_page": 10,
    "total": 50
}
```

# Laravel Todo List API 📝

A simple and clean Laravel API for managing a todo list. This API allows you to create and manage todos, export them to Excel with filters, and retrieve summarized data for a dashboard chart.

## Features

* CRUD operations for Todo items (Create, Read)
* Excel export with dynamic filtering
* Chart summary endpoint

### 1. Todo Management

This endpoint handles the creation and listing of todo items.

#### **Create a new Todo**

* **Endpoint:** `POST /todo`
* **Description:** Adds a new todo item to the list.
* **Example Body:**
    ```json
    {
      "title": "Design new dashboard",
      "description": "Complete the Figma mockups for the v2 dashboard.",
      "priority": "high",
      "status": "pending"
    }
    ```
---

### 2. Excel Export

This endpoint provides a downloadable Excel file of the todo list, with optional filters.

* **Endpoint:** `GET /excel`
* **Description:** Exports todo items to an `.xlsx` file.
* **Query Parameters (Filters):**
    * `status` (optional): Filter the export by status.
        * Example values: `pending`, `completed`, `in-progress`

* **Example Usage:**
    * To get all todos:
        ```
        GET http://localhost:8000/excel
        ```
    * To get only pending todos:
        ```
        GET http://localhost:8000/excel?status=pending
        ```

---

### 3. Chart Dashboard Summary

This endpoint provides summarized data ready to be used in a dashboard chart.

* **Endpoint:** `GET /chart`
* **Description:** Gets a summary of todo data grouped by a specific type.
* **Query Parameters:**
    * `type` (required): The data to summarize.
        * Example values: `priority`, `status`

* **Example Usage (by Priority):**
    * **Request:**
        ```
        GET http://localhost:8000/chart?type=priority
        ```
    * **Example Response:**
        ```json
        {
          "priority_summary": {
                "low": 11,
                "medium": 17,
                "high": 7
            }
        }
        ```

* **Example Usage (by Status):**
    * **Request:**
        ```
        GET http://localhost:8000/chart?type=status
        ```
    * **Example Response:**
        ```json
        {
          "status_summary": {
                "pending": 12,
                "open": 5,
                "in_progress": 10,
                "completed": 8
            }
        }
        ```

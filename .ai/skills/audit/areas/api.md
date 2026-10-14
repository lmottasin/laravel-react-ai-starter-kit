# API and MCP audit

Use when the app has JSON endpoints or an MCP server (`routes/ai.php`).

- Replies not using the `ApiResponse` shape or not going through API Resources.
- Wrong status codes (200 for creates, 200 for errors).
- HTML error pages returned to JSON clients.
- Write endpoints without a Form Request.
- Filter, sort or include parameters passed straight into queries without an allow-list.
- Missing rate limits on login, AI, upload and MCP endpoints.
- MCP tools without authentication, without authorization checks, or exposing more data than the tool needs.
- Missing tests for each endpoint and MCP tool, including 401 and 403.

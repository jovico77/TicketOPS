// =====================================
// TICKETOPS DATABASE v1.0
// =====================================

// ==========================
// ROLES
// ==========================

Table roles {
  id int [pk, increment]
  name varchar(50) [unique, not null]
  description text

  created_at timestamp
  updated_at timestamp
}

// ==========================
// USERS
// ==========================

Table users {
  id bigint [pk, increment]

  name varchar(100) [not null]
  email varchar(150) [unique, not null]
  password varchar(255) [not null]

  role_id int [not null, ref: > roles.id]

  created_at timestamp
  updated_at timestamp
}

// ==========================
// TICKET STATUS
// ==========================

Table ticket_status {
  id int [pk, increment]

  name varchar(50) [unique, not null]
  color varchar(20)
  display_order int

  created_at timestamp
  updated_at timestamp
}

// ==========================
// PRIORITIES
// ==========================

Table priorities {
  id int [pk, increment]

  name varchar(30) [unique, not null]
  color varchar(20)

  created_at timestamp
  updated_at timestamp
}

// ==========================
// CATEGORIES
// ==========================

Table categories {
  id int [pk, increment]

  name varchar(50) [unique, not null]
  description text

  created_at timestamp
  updated_at timestamp
}

// ==========================
// TICKETS
// ==========================

Table tickets {
  id bigint [pk, increment]

  title varchar(200) [not null]
  description text

  status_id int [not null, ref: > ticket_status.id]
  priority_id int [not null, ref: > priorities.id]
  category_id int [not null, ref: > categories.id]

  created_by bigint [not null, ref: > users.id]
  assigned_to bigint [ref: > users.id]

  created_at timestamp
  updated_at timestamp
  closed_at timestamp
}

// ==========================
// COMMENTS
// ==========================

Table comments {
  id bigint [pk, increment]

  ticket_id bigint [not null, ref: > tickets.id]
  user_id bigint [not null, ref: > users.id]

  comment text [not null]

  created_at timestamp
  updated_at timestamp
}
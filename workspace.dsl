workspace "Colibri" "Use-case diagram of Colibri" {
    !docs docs

    model {
        administrator = person "Administrator" "System administrator"
        user = person "User" "Basic system user"

        enterprise "Colibri" {
            colibri = softwaresystem "Colibri" "Library Management System" {
                usersContainer = container "Users" "Component responsible for user management" {
                    userList = component "List users" "View all users list"
                    userAdd = component "Add user" "Create a user"
                    userView = component "View user" "View user details"
                    userEdit = component "Edit user" "Edit user details"
                    userDelete = component "Delete user" "Delete user from the system"
                }
                libraryContainer = container "Library" "Component responsible for library management" {
                    bookList = component "List books" "View all books list"
                    bookAdd = component "Add book" "Create a book"
                    bookView = component "View book" "View book details"
                    bookEdit = component "Edit book" "Edit book details"
                    bookDelete = component "Delete book" "Delete book from the system"
                    bookReserve = component "Reserve" "Create reservation for a book"
                    bookHide = component "Hide book" "Hide book from the books list"
                }
                reservationsContainer = container "Reservations" "Component responsible for reservation management" {
                    reservationList = component "List reservations" "View all reservations list"
                    reservationMyList = component "List my reservations" "View my reservations list"
                    reservationAdd = component "Add reservation" "Create a reservation"
                    reservationView = component "View reservation" "View reservation details"
                    reservationEdit = component "Edit reservation" "Edit reservation details"
                    reservationDelete = component "Delete reservation" "Delete reservation from the system"
                    reservationCancel = component "Cancel reservation" "Cancel the upcoming reservation"
                }
                authorsContainer = container "Authors" "Component responsible for author management" {
                    authorList = component "List authors" "View all authors list"
                    authorAdd = component "Add author" "Create a author"
                    authorView = component "View author" "View author details"
                    authorEdit = component "Edit author" "Edit author details"
                    authorDelete = component "Delete author" "Delete author from the system"
                }
                categoriesContainer = container "Categories" "Component responsible for category management" {
                    categoryList = component "List categories" "View all categories list"
                    categoryAdd = component "Add category" "Create a category"
                    categoryView = component "View category" "View category details"
                    categoryEdit = component "Edit category" "Edit category details"
                    categoryDelete = component "Delete category" "Delete category from the system"
                }
            }
        }

        administrator -> colibri "uses Colibri to manage users, authors, categories, books and reservations"

        administrator -> usersContainer "can list, add, view, edit and delete"
        administrator -> userList "can"
        administrator -> userAdd "can"
        administrator -> userView "can"
        administrator -> userEdit "can"
        administrator -> userDelete "can"

        administrator -> libraryContainer "can list, add, view, edit, delete, make a reservation and hide a book"
        administrator -> bookList "can"
        administrator -> bookAdd "can"
        administrator -> bookView "can"
        administrator -> bookEdit "can"
        administrator -> bookDelete "can"
        administrator -> bookReserve "can"
        administrator -> bookHide "can"

        administrator -> reservationsContainer "can list all and personal reservations, add, view, edit, delete and cancel"
        administrator -> reservationList "can"
        administrator -> reservationMyList "can"
        administrator -> reservationAdd "can"
        administrator -> reservationView "can"
        administrator -> reservationEdit "can"
        administrator -> reservationDelete "can"
        administrator -> reservationCancel "can"

        administrator -> authorsContainer "can list, add, view, edit and delete"
        administrator -> authorList "can"
        administrator -> authorAdd "can"
        administrator -> authorView "can"
        administrator -> authorEdit "can"
        administrator -> authorDelete "can"

        administrator -> categoriesContainer "can list, add, view, edit and delete"
        administrator -> categoryList "can"
        administrator -> categoryAdd "can"
        administrator -> categoryView "can"
        administrator -> categoryEdit "can"
        administrator -> categoryDelete "can"

        user -> colibri "uses Colibri to create book reservations"

        user -> libraryContainer "can list, view and make a reservation"
        user -> bookList "can"
        user -> bookView "can"
        user -> bookReserve "can"

        user -> reservationsContainer "can list personal reservations, add, view, edit and cancel"
        user -> reservationMyList "can"
        user -> reservationAdd "can"
        user -> reservationView "can"
        user -> reservationEdit "can"
        user -> reservationCancel "can"
    }
    views {
        systemcontext colibri "ColibriSystemContext" {
            include *
            autoLayout lr
        }
        container colibri "SystemContainer" {
            include *
            autoLayout lr
        }
        component usersContainer "UsersContainerComponents" {
            include *
            autoLayout lr
        }
        component libraryContainer "LibraryContainerComponents" {
            include *
            autoLayout lr
        }
        component reservationsContainer "ReservationsContainerComponents" {
            include *
            autoLayout lr
        }
        component authorsContainer "AuthorsContainerComponents" {
            include *
            autoLayout lr
        }
        component categoriesContainer "CategoriesContainerComponents" {
            include *
            autoLayout lr
        }

        theme default
    }
}

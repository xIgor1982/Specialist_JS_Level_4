const { ApolloServer, gql } = require('apollo-server')

// A schema is a collection of type definitions (hence "typeDefs")
// that together define the "shape" of queries that are executed against
// your data.
const typeDefs = gql`
	# Comments in GraphQL strings (such as this one) start with the hash (#) symbol.

	# This "Book" type defines the queryable fields for every book in our data source.
	type Book {
		title: String
		author: String
	}

	type Mutation {
		addBook(title: String, author: String): Book
	}

	type Mutation {
		deleteBook(title: String): Book
	}

	# The "Query" type is special: it lists all of the available queries that
	# clients can execute, along with the return type for each. In this
	# case, the "books" query returns an array of zero or more Books (defined above).
	type Query {
		books: [Book]
	}
	type Query {
		searchbooks(title: String): [Book]
	}
`

let books = [
	{
		title: 'The Awakening',
		author: 'Kate Chopin',
	},
	{
		title: 'City of Glass',
		author: 'Paul Auster',
	},
]

// Resolvers define the technique for fetching the types defined in the
// schema. This resolver retrieves books from the "books" array above.
const resolvers = {
	Query: {
		books: () => {
			return books
		},
		searchbooks: (_, book) => {
			console.log('Запрос по названию книги:', book)
			return books.filter(item => item.title.includes(book.title))
		},
	},
	Mutation: {
		addBook: (_, book) => {
			console.log('Данные:', book)
			books.push(book)
			return {
				code: 200,
				success: true,
				...book,
				message: `Книга добавлена`,				
			}
		},
		deleteBook: (_, book) => {
			console.log('Удалены:', book)
			books = books.filter(item => item.title != book.title)
			return {
				code: 200,
				success: true,
				...book,
				message: `Книги удалены`,
			}
		},
	},
}

// The ApolloServer constructor requires two parameters: your schema
// definition and your set of resolvers.
const server = new ApolloServer({ typeDefs, resolvers })

// The `listen` method launches a web server.
server.listen().then(({ url }) => {
	console.log(`🚀  Server ready at ${url}`)
})

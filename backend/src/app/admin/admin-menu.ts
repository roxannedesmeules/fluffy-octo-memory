import { NbMenuItem } from "@nebular/theme";

export const MENU_ITEMS: NbMenuItem[] = [
	{
		title : "Dashboard",
		icon  : "nb-home",
		link  : "/admin/dashboard",
		home  : true,
	},
	{
		title    : "Categories",
		icon     : "nb-list",
		link     : "/admin/categories",
		children : [
			{ title : "Create category", link : "/admin/categories/create" },
			{ title : "View all", link : "/admin/categories" },
			{ title : "View active", link : "/admin/categories" },
		],
	},
	{
		title    : "Posts",
		icon     : "nb-compose",
		link     : "/admin/posts",
		children : [
			{ title : "Create post", link : "/admin/posts/create" },
			{ title : "View all", link : "/admin/posts" },
			{ title : "View published", link : "/admin/posts" },
		],
	},
];

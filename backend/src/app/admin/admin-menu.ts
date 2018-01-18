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
		icon     : "nb-grid-b-outline",
		link     : "/admin/category",
		children : [
			{ title : "Create category", link : "/admin/category/create" },
			{ title : "View all", link : "/admin/category/list" },
		],
	},
	{
		title    : "Posts",
		icon     : "nb-compose",
		link     : "/admin/posts",
		children : [
			{ title : "Create post", link : "/admin/post/create" },
			{ title : "View all", link : "/admin/post/list" },
		],
	},
];

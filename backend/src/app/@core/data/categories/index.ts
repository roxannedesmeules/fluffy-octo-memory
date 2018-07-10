// models
import { CategoryService } from "@core/data/categories/category.service";
import { DetailResolve, FullListResolve, PartialListResolve } from "@core/data/categories/resolvers";

export * from "./category-lang.model";
export * from "./category.model";
export * from "./category.filters";

// services
export * from "./category.service";

// resolvers
export * from "./resolvers";

export const SERVICES = [
	CategoryService,

	DetailResolve,
	FullListResolve,
	PartialListResolve,
];
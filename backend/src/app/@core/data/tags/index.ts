import { DetailResolve, FullListResolve, PartialListResolve } from "@core/data/tags/resolvers";
import { TagService } from "@core/data/tags/tag.service";

export * from "./tag.model";
export * from "./tag-lang.model";

export * from "./tag.filters";
export * from "./tag.service";

export * from "./resolvers";

export const SERVICES = [
	TagService,

	DetailResolve,
	PartialListResolve,
	FullListResolve,
];
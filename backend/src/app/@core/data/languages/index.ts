import { LangService } from "@core/data/languages/lang.service";
import { LanguageResolve } from "@core/data/languages/resolvers/language.resolve";

export * from "./lang.model";

export * from "./lang.service";

export * from "./resolvers/language.resolve";

export const SERVICES = [
	LangService,
	LanguageResolve,
];
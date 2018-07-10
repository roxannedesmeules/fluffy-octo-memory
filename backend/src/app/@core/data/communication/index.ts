import { DetailResolve, FullListResolve } from "@core/data/communication/resolvers";
import { CommunicationService } from "./communication.service";

export * from "./communication.model";
export * from "./communication.filters";
export * from "./communication.service";

export * from "./resolvers";

export const SERVICES = [
	CommunicationService,

	DetailResolve,
	FullListResolve,
];
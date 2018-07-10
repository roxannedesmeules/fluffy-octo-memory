import { AuthService } from "@core/data/users/auth.service";
import { MeResolve } from "@core/data/users/resolvers";
import { UserProfileService } from "@core/data/users/user-profile.service";
import { UserService } from "@core/data/users/user.service";

// authentication
export * from "./auth.form";
export * from "./auth.service";

// user
export * from "./user.model";
export * from "./user.service";

// user model
export * from "./user-profile.model";
export * from "./user-profile.service";

// misc
export * from "./author.model";

// resolvers
export * from "./resolvers";

export const SERVICES = [
	AuthService,
	UserService,
	UserProfileService,
	MeResolve,
];
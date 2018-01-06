import { TestBed, async, inject } from "@angular/core/testing";

import { RegularAuthGuard } from "./regular-auth.guard";

describe("RegularAuthGuard", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ RegularAuthGuard ],
		});
	});

	it("should ...", inject([ RegularAuthGuard ], ( guard: RegularAuthGuard ) => {
		expect(guard).toBeTruthy();
	}));
});

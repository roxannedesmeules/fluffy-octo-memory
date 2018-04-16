import { TestBed, inject } from "@angular/core/testing";

import { PartialListResolve } from "./partial-list.resolve";

describe("PartialListResolve", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ PartialListResolve ],
		});
	});

	it("should be created", inject([ PartialListResolve ], ( service: PartialListResolve ) => {
		expect(service).toBeTruthy();
	}));
});

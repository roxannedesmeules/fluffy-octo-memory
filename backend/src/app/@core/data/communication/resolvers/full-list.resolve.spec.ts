import { TestBed, inject } from "@angular/core/testing";

import { FullListResolve } from "./full-list.resolve";

describe("FullListResolve", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ FullListResolve ],
		});
	});

	it("should be created", inject([ FullListResolve ], ( service: FullListResolve ) => {
		expect(service).toBeTruthy();
	}));
});

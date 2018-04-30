import { TestBed, inject } from "@angular/core/testing";

import { MeResolve } from "./me.resolve";

describe("MeResolve", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ MeResolve ],
		});
	});

	it("should be created", inject([ MeResolve ], ( service: MeResolve ) => {
		expect(service).toBeTruthy();
	}));
});

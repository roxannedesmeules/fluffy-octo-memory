import { TestBed, inject } from "@angular/core/testing";

import { StatusResolve } from "./status.resolve";

describe("StatusResolve", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ StatusResolve ],
		});
	});

	it("should be created", inject([ StatusResolve ], ( service: StatusResolve ) => {
		expect(service).toBeTruthy();
	}));
});

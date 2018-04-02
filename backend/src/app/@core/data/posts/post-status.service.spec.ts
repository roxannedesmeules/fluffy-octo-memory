import { TestBed, inject } from "@angular/core/testing";

import { PostStatusService } from "./post-status.service";

describe("PostStatusService", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ PostStatusService ],
		});
	});

	it("should be created", inject([ PostStatusService ], ( service: PostStatusService ) => {
		expect(service).toBeTruthy();
	}));
});

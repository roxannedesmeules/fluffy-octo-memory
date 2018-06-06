import { TestBed, inject } from "@angular/core/testing";

import { PostListResolve } from "./post-list.resolve";

describe("PostListResolve", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ PostListResolve ],
		});
	});

	it("should be created", inject([ PostListResolve ], ( service: PostListResolve ) => {
		expect(service).toBeTruthy();
	}));
});

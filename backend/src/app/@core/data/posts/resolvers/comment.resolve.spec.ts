import { TestBed, inject } from "@angular/core/testing";

import { CommentResolve } from "./comment.resolve";

describe("CommentResolve", () => {
	beforeEach(() => {
		TestBed.configureTestingModule({
			providers : [ CommentResolve ],
		});
	});

	it("should be created", inject([ CommentResolve ], ( service: CommentResolve ) => {
		expect(service).toBeTruthy();
	}));
});

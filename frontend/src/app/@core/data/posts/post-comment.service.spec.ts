import { HttpClientTestingModule, HttpTestingController } from "@angular/common/http/testing";
import { TestBed } from "@angular/core/testing";

import { PostCommentService } from "./post-comment.service";
import { Post } from "@core/data/posts/post.model";
import { ErrorResponse } from "@core/data/error-response.model";

describe("PostCommentService", () => {
    let service: PostCommentService;
    let http: HttpTestingController;

    beforeEach(() => {
        TestBed.configureTestingModule({
            imports   : [ HttpClientTestingModule ],
            providers : [ PostCommentService ],
        });

        service = TestBed.get(PostCommentService);
        http    = TestBed.get(HttpTestingController);
    });

    afterEach(() => {
        http.verify();
    });

    it("should be created", () => {
        expect(service).toBeTruthy();
    });

    describe("#createForPost", () => {
        it("should return an Observable<Post>", () => {
            const body = { author: "test", email: "test@test.com", comment: "test hello world" };
            const post = { id: 1, title: "test 1", slug: "test-1", summary: "test 1", content: "test 1", comments: {count: 0, list: []} };

            service.createForPost(post.id, body).subscribe(result => {
                expect(result).toEqual(jasmine.any(result));
                expect(result).toEqual(jasmine.objectContaining(post));
            });

            const req = http.expectOne(req => req.method === "POST" && req.url === "posts/1/comments");
                  req.flush(post, { status: 201, statusText: "Created" });

            expect(req.request.method).toBe("POST");
            expect(req.request.body).toEqual(body);
        });

        it("should return an error if comment is missing", () => {
            const body = { author: "test", email: "test@test.com" };
            const post = { id: 1, title: "test 1", slug: "test-1", summary: "test 1", content: "test 1", comments: {count: 0, list: []} };

            service.createForPost(post.id, body).subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(422);
                        expect(err.form_error).toEqual(jasmine.objectContaining({ comment : [ "ERR_FIELD_VALUE_REQUIRED" ] }));
                    }
            );

            const req = http.expectOne(req => req.method === "POST" && req.url === "posts/1/comments");
                  req.flush({code: 422, error: { comment : [ "ERR_FIELD_VALUE_REQUIRED" ] }}, { status: 422, statusText: "Unprocessabled" });

            expect(req.request.method).toBe("POST");
            expect(req.request.body).toEqual(body);
        });

        it("should return an error if email is missing", () => {
            const body = { author: "test", comment: "test here" };
            const post = { id: 1, title: "test 1", slug: "test-1", summary: "test 1", content: "test 1", comments: {count: 0, list: []} };

            service.createForPost(post.id, body).subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(422);
                        expect(err.form_error).toEqual(jasmine.objectContaining({ email : [ "ERR_FIELD_VALUE_REQUIRED" ] }));
                    }
            );

            const req = http.expectOne(req => req.method === "POST" && req.url === "posts/1/comments");
                  req.flush({code: 422, error: { email : [ "ERR_FIELD_VALUE_REQUIRED" ] }}, { status: 422, statusText: "Unprocessabled" });

            expect(req.request.method).toBe("POST");
            expect(req.request.body).toEqual(body);
        });

        it("should return an error if author is missing", () => {
            const body = { email: "test@test.com", comment: "test hello world" };
            const post = { id: 1, title: "test 1", slug: "test-1", summary: "test 1", content: "test 1", comments: {count: 0, list: []} };

            service.createForPost(post.id, body).subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(422);
                        expect(err.form_error).toEqual(jasmine.objectContaining({ author : [ "ERR_FIELD_VALUE_REQUIRED" ] }));
                    }
            );

            const req = http.expectOne(req => req.method === "POST" && req.url === "posts/1/comments");
            req.flush({code: 422, error: { author : [ "ERR_FIELD_VALUE_REQUIRED" ] }}, { status: 422, statusText: "Unprocessabled" });

            expect(req.request.method).toBe("POST");
            expect(req.request.body).toEqual(body);
        });
    });

    describe("#findAll", () => {
        it("should return a 501 error", () => {
            service.findAll().subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#findOne", () => {
        it("should return a 501 error", () => {
            service.findOne().subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#findById", () => {
        it("should return a 501 error", () => {
            service.findById().subscribe(
                    () => {},
                    err => {
                        expect(err).toEqual(jasmine.any(ErrorResponse));
                        expect(err.code).toEqual(501);
                    });
        });
    });

    describe("#mapModel", () => {
        it("should transform an object into a Post", () => {
            const data  = { id: 1, title: "test 1", slug: "test-1", summary: "test 1", content: "test 1" };
            const model = service.mapModel(data);

            expect(model).toEqual(jasmine.objectContaining(data));
            expect(model).toEqual(jasmine.any(Post));
        });
    });

    describe("#url", () => {
        it("should replace :baseUrl/:id/:modelName into posts/1/comments", () => {
            expect(service.url(1, ":baseUrl/:id/:modelName")).toEqual("posts/1/comments");
        });
    });
});

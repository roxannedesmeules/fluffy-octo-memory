import { Post } from "@core/data/posts/post.model";

describe("PostModel", () => {
    let model: Post;

    beforeEach(() => {
        model = new Post({
            id              : 1,
            featured        : 0,
            comment_enabled : 1,
            title           : "This is a test",
            slug            : "post-slug",
            summary         : "This is the summary of your post and should be less than 160 chars",
            content         : "Tiramisu jelly cupcake oat cake jelly beans. Pastry brownie candy macaroon oat cake carrot cake. Marshmallow caramels icing biscuit biscuit gummi bears. Jelly-o toffee gummies toffee pie toffee candy canes. Bonbon sesame snaps sweet. Cotton candy macaroon cupcake halvah brownie muffin wafer cheesecake. Gummi bears toffee marshmallow. Chocolate cake pie soufflé cake jelly-o gummi bears muffin pudding. Cheesecake marzipan fruitcake chupa chups oat cake chocolate bar. Jelly beans dragée brownie caramels wafer gingerbread. Tootsie roll chocolate bar tart topping jelly beans apple pie. Tiramisu bear claw sweet roll pastry pudding muffin.",
            category        : { id : 1, name : "category", slug : "category-slug" },
            cover           : { url : "https://via.placeholder.com/350x150", alt : "test" },
            tags            : [ { id : 1, name : "angular", slug : "angular" } ],
            author          : {
                id        : 1,
                fullname  : "Roxanne Desmeules",
                firstname : "Roxanne",
                lastname  : "Desmeules",
                picture   : "https://via.placeholder.com/350x150",
                biography : "hello world, i'm awesome and a web dev",
                job_title : "web dev",
            },
            comments : {
                count:0,
            },
        });
    });

    afterEach(() => {
        model = null;
    });

    describe("#getSummary", () => {
        it("should return the summary", () => {
            expect(model.getSummary()).toEqual(model.summary);
        });

        it("should return the first 160 chars of the description", () => {
            const expected = model.content.substring(0, 160);
            model.summary  = "";

            expect(model.getSummary()).toEqual(expected);
        });
    });

    describe("#getUrl", () => {
        it("should return app URL; /blog/category-slug/post-slug", () => {
            expect(model.getUrl()).toEqual("/blog/category-slug/post-slug");
        });

        it("should return an empty string if no ID", () => {
            let temp = new Post();

            expect(temp.getUrl()).toEqual("");
        });
    });

    describe("#commentsAreEnabled", () => {
        it("should return true if comment_enabled set to 1", () => {
            expect(model.commentsAreEnabled()).toBeTruthy();
        });

        it("should return false if comment_enabled set to 0", () => {
            model.comment_enabled = 0;

            expect(model.commentsAreEnabled()).toBeFalsy();
        });
    });
});

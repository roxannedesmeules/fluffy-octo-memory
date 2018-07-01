<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post_comment`.
 */
class m180610_172711_create_post_comment_table extends Migration
{
	public static $relations = [
		[ "column" => "post_id", "rel_table" => "post", "rel_column" => "id" ],
		[ "column" => "lang_id", "rel_table" => "lang", "rel_column" => "id" ],
		[ "column" => "reply_comment_id", "rel_table" => "post_comment", "rel_column" => "id" ],
		[ "column" => "user_id", "rel_table" => "user", "rel_column" => "id" ],
	];

	/** @inheritdoc */
	public function safeUp ()
	{
		$this->createTable("post_comment", [
			"id"               => $this->primaryKey(),
			"post_id"          => $this->integer()->notNull(),
			"lang_id"          => $this->integer()->notNull(),
			"reply_comment_id" => $this->integer()->defaultValue(null),
			"user_id"          => $this->integer()->defaultValue(null),
			"author"           => $this->string(140)->defaultValue(null),
			"email"            => $this->string(255)->defaultValue(null),
			"comment"          => $this->text()->notNull(),
			"is_approved"       => $this->smallInteger(1)->defaultValue(0),
			"created_on"       => $this->dateTime(),
			"approved_on"      => $this->dateTime(),
		]);

		foreach (self::$relations as $relation) {
			$key = "post_comment-{$relation[ 'column' ]}";

			$this->createIndex("idx-$key", "post_comment", $relation[ "column" ]);
			$this->addForeignKey("fk-$key", "post_comment", $relation[ "column" ], $relation[ "rel_table" ], $relation[ "rel_column" ]);
		}
	}

	/** @inheritdoc */
	public function safeDown ()
	{
		foreach (self::$relations as $relation) {
			$key = "post_comment-{$relation[ 'column' ]}";

			$this->dropForeignKey("fk-$key", "post_comment", $relation[ "column" ]);
			$this->dropIndex("idx-$key", "post_comment");
		}

		$this->dropTable("post_comment");
	}
}

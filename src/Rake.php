<?php

namespace TuanAnh0907\Rake;

class Rake
{
    private $stopwords;

    private $paragraph;

    public function __construct($paragraph, $stopwords)
    {
        $this->paragraph = $paragraph;
        $this->stopwords = $stopwords;
    }

    public function extract(): array
    {
        $sentences = $this->split_sentences();

        $phrases_arr = $this->get_phrases($sentences);

        $scores = $this->get_scores($phrases_arr);

        $keywords = $this->get_keywords($phrases_arr, $scores);

        arsort($keywords);

        return $keywords;
    }

    /**
     * Split text into sentences with punctuation by special characters
     */

    private function split_sentences()
    {
        return preg_split('/[.?!,;\/\-"\'()\n\r\t]+/u', $this->paragraph);
    }

    /**
     * Split sentences into phrases by loaded stop words
     *
     * @param array $sentences Array of sentences
     */

    private function get_phrases(array $sentences = []): array
    {
        $phrases_arr = [];

        $regex = '/\b' . implode('\b|\b', $this->stopwords) . '\b/iu';
//        var_dump($regex);
//        die();
        foreach ($sentences as $sentence) {
            if (trim($sentence)) {
                $phraseItem = preg_replace($regex, "|", mb_strtolower(trim($sentence))); // thay thế các từ dừng trong câu thành kí tự "|"
                $phraseItem = explode("|", $phraseItem); // cắt câu thành mảng ngăn cách bởi "|"
                foreach ($phraseItem as $item) {
                    if (trim($item)) {
                        if (!is_numeric($item)) {
                            $phrases_arr[] = trim($item);
                        }
                    }
                }
            }
        }
        return $phrases_arr;
    }

    /**
     * @param string $phrase Phrase to be split into words
     */
    private static function split_phrase(string $phrase): array
    {
        return explode(' ', $phrase); // cắt cụm từ thành các từ bằng dấu space
    }

    /**
     * Calculate score for each word
     *
     * @param array $phrases_arr Array containing individual phrases
     */

    private function get_scores(array $phrases_arr): array
    {
        $frequencies = []; // tần suất
        $degrees = []; // bậc

        foreach ($phrases_arr as $p) {

            $words = self::split_phrase($p); // tách cụm từ thành mảng các từ
            $words_count = count($words); // đếm số lượng từ trong cụm
            $words_degree = $words_count - 1; // bậc của cụm từ bằng sl trừ 1

            foreach ($words as $w) {
                $frequencies[$w] = $frequencies[$w] ?? 0; // giá trị tần suất của phần tử trong mảng $frequencies[] có key ~ từ bằng 0 hoặc giữ nguyên nếu tồn tại
                ++$frequencies[$w]; // tăng giá trị của phần tử key bằng từ lên 1
                $degrees[$w] = $degrees[$w] ?? 0; // kiểm tra trong mảng $degrees[] có phần tử key ~ từ ko, = 0 hoặc giữ nguyên nếu tồn tại
                $degrees[$w] += $words_degree; // bậc của key ~ từ : cộng thêm bậc của cụm từ vào giá trị ban đầu

            }

        }

        foreach ($frequencies as $word => $freq) {
            $degrees[$word] += $freq; // bậc phần tử key ~ từ cộng thêm tần suất
        }

        $scores = array();

        foreach ($frequencies as $word => $freq) {
            $scores[$word] = $scores[$word] ?? 0; // điểm của từ bằng 0 hoặc giữ nguyên nếu tồn tại trong mảng $scores[]
            $scores[$word] = $degrees[$word] / (float)$freq;  // điểm của từ bằng bậc chia tần suất
        }

        return $scores;
    }

    /**
     * Calculate score for each phrase by words scores
     *
     * @param array $phrases_arr Array of phrases (optimally) returned by get_phrases() method
     * @param array $scores Array of words and their scores returned by get_scores() method
     */
    private function get_keywords(array $phrases_arr, array $scores): array
    {
        $keywords = [];

        foreach ($phrases_arr as $phrases) {
            $keywords[$phrases] = $keywords[$phrases] ?? 0; // phần tử key ~ cụm từ bằng 0 hoặc giữ nguyên nếu tồn tại
            $words = self::split_phrase($phrases); // cắt cụm từ thành mảng các từ
            $score = 0;

            foreach ($words as $w) {
                $score += $scores[$w]; // điểm của cụm từ cộng thêm điểm của từ lấy từ mảng điểm với key ~ từ
            }

            $keywords[$phrases] = $score; // gán điểm cho phần tử key ~ cụm từ
        }

        return $keywords;
    }
}
